<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Lokasi Flooring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {

            /* Paksa Landscape A4 */
            @page {
                size: A4 landscape;
                margin: 5mm;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }
        }

        .break-inside-avoid {
            page-break-inside: avoid;
        }
    </style>
</head>

<body class="bg-white text-black font-sans">

    {{-- 
        GRID LAYOUT - Same as Standard
    --}}
    <div class="grid grid-cols-4 border-t border-l border-black w-full mx-auto">
        @foreach ($locations as $loc)
            <div
                class="flex flex-col items-center justify-center p-6 border-b border-r border-black break-inside-avoid h-[380px]">

                {{-- QR CODE AREA --}}
                <div class="flex-grow flex items-center justify-center">
                    {!! QrCode::size(220)->generate($loc->location_code) !!}
                </div>

                {{-- LOCATION ID AREA --}}
                <div class="text-center w-full mt-4">
                    <span class="font-bold text-[36px] leading-none text-black block">
                        {{ $loc->location_code }}
                    </span>
                </div>

            </div>
        @endforeach
    </div>

</body>

</html>
