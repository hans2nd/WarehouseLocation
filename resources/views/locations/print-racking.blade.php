<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Lokasi Racking</title>
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 5mm;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
            
            .page-break {
                page-break-after: always;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: white;
            color: black;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 3mm;
            margin: 0 auto;
        }

        .page-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2mm;
        }

        /* Grid Container */
        .rack-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border: 1px solid black;
            width: 100%;
        }

        /* Arrow Header Row */
        .arrow-cell {
            border: 1px solid black;
            padding: 2mm;
            text-align: center;
            height: 20mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .circle-marker {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .arrow-left {
            width: 30mm;
            height: 10mm;
        }

        .arrow-right {
            width: 30mm;
            height: 10mm;
        }

        /* QR Cell */
        .qr-cell {
            border: 1px solid black;
            padding: 2mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 45mm;
        }

        .qr-cell.empty {
            background: white;
        }

        .qr-code svg {
            width: 28mm;
            height: 28mm;
        }

        .location-code {
            font-size: 11px;
            font-weight: bold;
            margin-top: 2mm;
        }

        /* Footer Row */
        .footer-cell {
            border: 1px solid black;
            padding: 2mm;
            text-align: center;
            height: 8mm;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .footer-marker {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @php
        // Default arrow direction
        $arrowDir = $arrowDirection ?? 'alternate';
        
        // 4 columns x 5 rows = 20 QR per page
        $qrPerPage = 20;
        $chunks = $locations->chunk($qrPerPage);
        $pageNumber = 0;
    @endphp

    @foreach($chunks as $chunk)
    @php
        $pageNumber++;
        $items = $chunk->values();
        
        // Fill to make complete rows (5 rows x 4 columns)
        $totalItems = count($items);
        $rows = [];
        for ($row = 0; $row < 5; $row++) {
            $rows[$row] = [];
            for ($col = 0; $col < 4; $col++) {
                $index = $row * 4 + $col;
                $rows[$row][$col] = $items[$index] ?? null;
            }
        }
    @endphp
    
    <div class="page {{ !$loop->last ? 'page-break' : '' }}">
        <div class="page-title">Kertas {{ $pageNumber }}</div>
        
        <div class="rack-grid">
            {{-- Header Row: Arrows --}}
            @for($col = 0; $col < 4; $col++)
            <div class="arrow-cell">
                <span class="circle-marker">O</span>
                @php
                    // Determine arrow direction for this column
                    if ($arrowDir === 'left') {
                        $showLeft = true;
                    } elseif ($arrowDir === 'right') {
                        $showLeft = false;
                    } else {
                        // Alternate: even columns = left, odd columns = right
                        $showLeft = ($col % 2 == 0);
                    }
                @endphp
                
                @if($showLeft)
                {{-- Left Arrow --}}
                <svg class="arrow-left" viewBox="0 0 100 30">
                    <polygon points="0,15 20,0 20,10 100,10 100,20 20,20 20,30" fill="black"/>
                </svg>
                @else
                {{-- Right Arrow --}}
                <svg class="arrow-right" viewBox="0 0 100 30">
                    <polygon points="100,15 80,0 80,10 0,10 0,20 80,20 80,30" fill="black"/>
                </svg>
                @endif
            </div>
            @endfor

            {{-- QR Code Rows (5 rows) --}}
            @foreach($rows as $row)
                @foreach($row as $loc)
                <div class="qr-cell {{ !$loc ? 'empty' : '' }}">
                    @if($loc)
                    <div class="qr-code">
                        {!! QrCode::size(100)->generate($loc->location_code) !!}
                    </div>
                    <span class="location-code">{{ $loc->location_code }}</span>
                    @endif
                </div>
                @endforeach
            @endforeach

            {{-- Footer Row: Circle Markers --}}
            @for($col = 0; $col < 4; $col++)
            <div class="footer-cell">
                <span class="footer-marker">O</span>
            </div>
            @endfor
        </div>
    </div>
    @endforeach
</body>

</html>
