<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    /**
     * تنزيل قالب CSV بسيط بحسب النوع
     */
    public function sample(string $type)
    {
        // قوالب الرؤوس الجديدة المتوافقة مع الهيكلة الحالية
        $headers = [
            'universities' => ['name','address','phone','is_active'],              // is_active: 1/0 أو true/false اختياري
            'colleges'     => ['university_name','name','is_active'],              // الجامعة تُعرّف بالاسم
            'majors'       => ['university_name','college_name','name','is_active'], // التخصص يُربط بكلية عبر الاسم + الجامعة
        ];

        abort_unless(isset($headers[$type]), 404);

        $csv = implode(',', $headers[$type]) . "\n";
        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$type.'_sample.csv"',
        ]);
    }

    /**
     * تنفيذ الاستيراد
     */
    public function run(Request $r)
    {
        $r->validate([
            'type' => 'required|in:universities,colleges,majors',
            'file' => 'required|file|mimes:xlsx,csv,txt',
        ]);

        // نقرأ الورقة الأولى
        $rows = Excel::toArray([], $r->file('file'))[0] ?? [];
        if (!$rows || count($rows) < 2) {
            return back()->withErrors(['file' => 'ملف فارغ أو غير صالح.']);
        }

        $header   = array_map(fn($h) => trim((string) $h), $rows[0]);
        $dataRows = array_slice($rows, 1);

        $created = $updated = 0;
        $type    = $r->type;

        DB::transaction(function () use ($type, $header, $dataRows, &$created, &$updated) {
            if ($type === 'universities') {
                // header: name,address,phone,is_active
                foreach ($dataRows as $row) {
                    $row = $this->rowAssoc($header, $row);
                    if (empty($row['name'])) { continue; }

                    $attrs = [
                        'address'   => $row['address']   ?? '',
                        'phone'     => $row['phone']     ?? null,
                        'is_active' => $this->toBool($row['is_active'] ?? 1),
                    ];

                    // upsert بواسطة الاسم
                    $u = University::updateOrCreate(
                        ['name' => $row['name']],
                        $attrs
                    );
                    $u->wasRecentlyCreated ? $created++ : $updated++;
                }
            }

            if ($type === 'colleges') {
                // header: university_name,name,is_active
                foreach ($dataRows as $row) {
                    $row = $this->rowAssoc($header, $row);
                    if (empty($row['university_name']) || empty($row['name'])) { continue; }

                    $uni = University::where('name', $row['university_name'])->first();
                    if (!$uni) { continue; }

                    $attrs = [
                        'is_active' => $this->toBool($row['is_active'] ?? 1),
                    ];

                    // upsert بواسطة (university_id + name)
                    $c = College::updateOrCreate(
                        ['university_id' => $uni->id, 'name' => $row['name']],
                        $attrs
                    );
                    $c->wasRecentlyCreated ? $created++ : $updated++;
                }
            }

            if ($type === 'majors') {
                // header: university_name,college_name,name,is_active
                foreach ($dataRows as $row) {
                    $row = $this->rowAssoc($header, $row);
                    if (empty($row['university_name']) || empty($row['college_name']) || empty($row['name'])) { continue; }

                    $uni = University::where('name', $row['university_name'])->first();
                    if (!$uni) { continue; }

                    $college = College::where('university_id', $uni->id)
                                      ->where('name', $row['college_name'])
                                      ->first();
                    if (!$college) { continue; }

                    $attrs = [
                        'is_active' => $this->toBool($row['is_active'] ?? 1),
                    ];

                    // upsert بواسطة (college_id + name)
                    $m = Major::updateOrCreate(
                        ['college_id' => $college->id, 'name' => $row['name']],
                        $attrs
                    );
                    $m->wasRecentlyCreated ? $created++ : $updated++;
                }
            }
        });

        return back()->with('success', "تم الاستيراد بنجاح. مُنشأ: {$created} | مُحدّث: {$updated}");
    }

    /**
     * تحويل الصف إلى مصفوفة بالاعتماد على الرؤوس
     */
    private function rowAssoc(array $header, array $row): array
    {
        $assoc = [];
        foreach ($header as $i => $key) {
            $assoc[$key] = isset($row[$i]) ? trim((string) $row[$i]) : null;
        }
        return $assoc;
    }

    /**
     * تحويل قيَم is_active النصية/الرقمية إلى boolean/0/1
     */
    private function toBool($val): int
    {
        if (is_null($val) || $val === '') return 1; // افتراضي: مفعل
        $v = strtolower(trim((string) $val));
        return in_array($v, ['1','true','yes','y','t','on','مفعل','فعال'], true) ? 1 : 0;
    }
}
