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
                display: none !important;
            }
        }

        .break-inside-avoid {
            page-break-inside: avoid;
        }
        
        /* Dynamic sizing with CSS variables */
        .qr-container svg {
            width: var(--qr-size, 220px) !important;
            height: var(--qr-size, 220px) !important;
        }
        
        .location-code {
            font-size: var(--font-size, 36px) !important;
        }
        
        /* Floating Control Panel */
        .control-panel {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border: 2px solid #333;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            min-width: 200px;
            font-family: Arial, sans-serif;
        }
        
        .control-panel h3 {
            font-size: 14px;
            margin-bottom: 12px;
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        
        .control-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 10px 16px;
            margin-bottom: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .control-btn:hover {
            background: #e9e9e9;
            border-color: #999;
        }
        
        .control-btn.primary {
            background: #2563eb;
            color: white;
            border-color: #1d4ed8;
        }
        
        .control-btn.primary:hover {
            background: #1d4ed8;
        }
        
        .control-section {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }
        
        .control-section:last-of-type {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .control-section h4 {
            font-size: 12px;
            color: #555;
            margin-bottom: 8px;
        }
        
        .slider-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 8px;
        }
        
        .slider-group label {
            font-size: 11px;
            color: #666;
            display: flex;
            justify-content: space-between;
        }
        
        .slider-group input[type="range"] {
            width: 100%;
            cursor: pointer;
        }
        
        .slider-value {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body class="bg-white text-black font-sans">

    {{-- Floating Control Panel --}}
    <div class="control-panel no-print">
        <h3>⚙️ Pengaturan Cetak</h3>
        
        {{-- Section: Ukuran --}}
        <div class="control-section">
            <h4>📐 Ukuran</h4>
            <div class="slider-group">
                <label>QR Code: <span id="qr-size-value" class="slider-value">220px</span></label>
                <input type="range" id="qr-size-slider" min="150" max="280" value="220" oninput="updateQRSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Font Lokasi: <span id="font-size-value" class="slider-value">36px</span></label>
                <input type="range" id="font-size-slider" min="20" max="48" value="36" oninput="updateFontSize(this.value)">
            </div>
        </div>
        
        <button class="control-btn primary" onclick="window.print()">
            🖨️ Cetak
        </button>
    </div>

    {{-- 
        GRID LAYOUT - Same as Standard
    --}}
    <div class="grid grid-cols-4 border-t border-l border-black w-full mx-auto">
        @foreach ($locations as $loc)
            <div
                class="flex flex-col items-center justify-center p-6 border-b border-r border-black break-inside-avoid h-[380px]">

                {{-- QR CODE AREA --}}
                <div class="flex-grow flex items-center justify-center qr-container">
                    {!! QrCode::size(220)->generate($loc->location_code) !!}
                </div>

                {{-- LOCATION ID AREA --}}
                <div class="text-center w-full mt-4">
                    <span class="font-bold leading-none text-black block location-code">
                        {{ $loc->location_code }}
                    </span>
                </div>

            </div>
        @endforeach
    </div>
    
    <script>
        // Update QR Code size dynamically
        function updateQRSize(value) {
            document.documentElement.style.setProperty('--qr-size', value + 'px');
            document.getElementById('qr-size-value').textContent = value + 'px';
        }
        
        // Update Font size dynamically
        function updateFontSize(value) {
            document.documentElement.style.setProperty('--font-size', value + 'px');
            document.getElementById('font-size-value').textContent = value + 'px';
        }
    </script>

</body>

</html>
