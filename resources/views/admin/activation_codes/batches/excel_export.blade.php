<table>
    <thead>
        <tr>
            <th>#</th>
            <th>الكود</th>
            <th>الحالة</th>
            <th>سياسة البدء</th>
            <th>تاريخ البدء</th>
            <th>صالح من</th>
            <th>صالح حتى</th>
        </tr>
    </thead>
    <tbody>
        @foreach($codes as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->code }}</td>
                <td>
                    @php
                        $st = [
                            'active'   => 'نشط',
                            'redeemed' => 'مُسترد',
                            'expired'  => 'منتهي',
                            'disabled' => 'موقوف',
                        ][$c->status] ?? $c->status;
                    @endphp
                    {{ $st }}
                </td>
                <td>{{ $c->start_policy === 'fixed_start' ? 'موعد ثابت' : 'عند التفعيل' }}</td>
                <td>{{ optional($c->starts_on)->format('Y-m-d') ?: '—' }}</td>
                <td>{{ optional($c->valid_from)->format('Y-m-d H:i') ?: '—' }}</td>
                <td>{{ optional($c->valid_until)->format('Y-m-d H:i') ?: '—' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
