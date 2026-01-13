<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Lokasi Gudang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {

            /* Paksa Landscape A4 */
            @page {
                size: A4 landscape;
                margin: 5mm;
                /* Margin kertas kecil agar muat banyak */
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

    {{-- Tombol Navigasi --}}
    {{-- <div class="no-print fixed top-4 right-4 z-50 flex gap-2">
        <button onclick="window.history.back()"
            class="bg-gray-500 text-white px-4 py-2 rounded shadow font-bold">Kembali</button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow font-bold">Cetak</button>
    </div> --}}

    {{-- 
        GRID LAYOUT 
    --}}
    <div class="grid grid-cols-4 border-t border-l border-black w-full mx-auto">
        @foreach ($locations as $loc)
            {{-- 
             PERUBAHAN DISINI:
             1. p-6 : Memberikan padding (jarak dalam) di sekeliling kotak.
             2. justify-between : Agar jarak terbagi rapi secara vertikal.
        --}}
            <div
                class="flex flex-col items-center justify-center p-6 border-b border-r border-black break-inside-avoid h-[380px]">

                {{-- 
                QR CODE AREA 
                Ukuran diubah ke 220 agar muat dengan padding.
                300px terlalu lebar untuk kolom A4 dibagi 4.
            --}}
                <div class="flex-grow flex items-center justify-center">
                    {!! QrCode::size(220)->generate($loc->location_code) !!}
                </div>

                {{-- 
                LOCATION ID AREA 
                mt-4 : Memberikan jarak (margin) antara QR dan Text
            --}}
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
