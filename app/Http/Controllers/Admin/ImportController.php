<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    public function index() {
        return view('admin.import.index');
    }

    public function sample(string $type) {
        // يُنشئ CSV بسيط للتحميل كقالب
        $headers = [
            'universities' => ['name','slug','code','primary_color','secondary_color'],
            'colleges'     => ['university_slug','name','code'],
            'majors'       => ['university_slug','college_code','name','code'],
        ];

        abort_unless(isset($headers[$type]), 404);
        $csv = implode(',', $headers[$type])."\n";
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$type.'_sample.csv"',
        ]);
    }

    public function run(Request $r) {
        $r->validate([
            'type' => 'required|in:universities,colleges,majors',
            'file' => 'required|file|mimes:xlsx,csv,txt',
        ]);

        $type = $r->type;
        $path = $r->file('file')->getRealPath();

        // نقرأ عبر PhpSpreadsheet مباشرة من خلال Maatwebsite\Excel
        $rows = Excel::toArray([], $r->file('file'))[0] ?? [];
        if (!$rows || count($rows) < 2) {
            return back()->withErrors(['file' => 'ملف فارغ أو غير صالح.']);
        }

        $header = array_map(fn($h)=>trim((string)$h), $rows[0]);
        $dataRows = array_slice($rows, 1);

        $created = $updated = 0;

        DB::transaction(function () use ($type, $header, $dataRows, &$created, &$updated) {
            if ($type === 'universities') {
                // header: name, slug, code, primary_color, secondary_color
                foreach ($dataRows as $row) {
                    $row = $this->rowAssoc($header, $row);
                    if (empty($row['name']) || empty($row['slug'])) continue;

                    $u = University::updateOrCreate(
                        ['slug' => Str::lower($row['slug'])],
                        [
                            'name' => $row['name'],
                            'code' => $row['code'] ?? null,
                            'primary_color' => $row['primary_color'] ?: '#0d6efd',
                            'secondary_color' => $row['secondary_color'] ?: '#6c757d',
                            'is_active' => true,
                        ]
                    );
                    $u->wasRecentlyCreated ? $created++ : $updated++;
                }
            }

            if ($type === 'colleges') {
                // header: university_slug, name, code
                foreach ($dataRows as $row) {
                    $row = $this->rowAssoc($header, $row);
                    if (empty($row['university_slug']) || empty($row['name'])) continue;

                    $uni = University::where('slug', Str::lower($row['university_slug']))->first();
                    if (!$uni) continue;

                    $c = College::updateOrCreate(
                        ['university_id' => $uni->id, 'code' => $row['code'] ?? null, 'name' => $row['name']],
                        ['is_active'=>true]
                    );
                    $c->wasRecentlyCreated ? $created++ : $updated++;
                }
            }

            if ($type === 'majors') {
                // header: university_slug, college_code, name, code
                foreach ($dataRows as $row) {
                    $row = $this->rowAssoc($header, $row);
                    if (empty($row['university_slug']) || empty($row['college_code']) || empty($row['name'])) continue;

                    $uni = University::where('slug', Str::lower($row['university_slug']))->first();
                    if (!$uni) continue;

                    $college = College::where('university_id',$uni->id)->where('code',$row['college_code'])->first();
                    if (!$college) continue;

                    $m = Major::updateOrCreate(
                        ['college_id'=>$college->id, 'name'=>$row['name']],
                        ['code'=>$row['code'] ?? null, 'is_active'=>true]
                    );
                    $m->wasRecentlyCreated ? $created++ : $updated++;
                }
            }
        });

        return back()->with('success', "تم الاستيراد بنجاح. مُنشأ: {$created} | مُحدّث: {$updated}");
    }

    private function rowAssoc(array $header, array $row): array {
        $assoc = [];
        foreach ($header as $i=>$key) {
            $assoc[$key] = isset($row[$i]) ? trim((string)$row[$i]) : null;
        }
        return $assoc;
    }
}
