<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Lokasi Double Deep</title>
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
                display: none !important;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            .arrow-cell {
                cursor: default !important;
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

        /* Grid Container */
        .dd-grid {
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
            background: #e5e7eb;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .arrow-cell:hover {
            background-color: #d1d5db;
        }

        .circle-marker {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 1mm;
        }

        .arrow-left, .arrow-right {
            width: 30mm;
            height: 10mm;
            transition: transform 0.3s ease;
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
            width: var(--qr-size, 28mm);
            height: var(--qr-size, 28mm);
        }

        .location-code {
            font-size: var(--font-size, 11px);
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
            background: #e5e7eb;
        }

        .footer-marker {
            font-size: 14px;
            font-weight: bold;
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
        
        .control-btn svg {
            width: 24px;
            height: 12px;
            margin-right: 8px;
        }
        
        .control-btn.primary {
            background: #2563eb;
            color: white;
            border-color: #1d4ed8;
        }
        
        .control-btn.primary:hover {
            background: #1d4ed8;
        }
        
        .hint {
            font-size: 11px;
            color: #666;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #eee;
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

<body>
    {{-- Floating Control Panel --}}
    <div class="control-panel no-print">
        <h3>⚙️ Pengaturan Cetak</h3>
        
        {{-- Section: Ukuran --}}
        <div class="control-section">
            <h4>📐 Ukuran</h4>
            <div class="slider-group">
                <label>QR Code: <span id="qr-size-value" class="slider-value">28mm</span></label>
                <input type="range" id="qr-size-slider" min="20" max="40" value="28" oninput="updateQRSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Font Lokasi: <span id="font-size-value" class="slider-value">11px</span></label>
                <input type="range" id="font-size-slider" min="8" max="18" value="11" oninput="updateFontSize(this.value)">
            </div>
        </div>
        
        {{-- Section: Arah Panah --}}
        <div class="control-section">
            <h4>🎯 Arah Panah</h4>
            <button class="control-btn" onclick="setAllArrows('left')">
                <svg viewBox="0 0 100 30">
                    <polygon points="0,15 20,0 20,10 100,10 100,20 20,20 20,30" fill="currentColor"/>
                </svg>
                Semua Kiri
            </button>
            <button class="control-btn" onclick="setAllArrows('right')">
                <svg viewBox="0 0 100 30">
                    <polygon points="100,15 80,0 80,10 0,10 0,20 80,20 80,30" fill="currentColor"/>
                </svg>
                Semua Kanan
            </button>
            <button class="control-btn" onclick="setAllArrows('alternate')">
                ↔ Bergantian
            </button>
        </div>
        
        <button class="control-btn primary" onclick="window.print()">
            🖨️ Cetak
        </button>
        <p class="hint">💡 Klik langsung pada panah untuk toggle arah individual</p>
    </div>

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
        
        <div class="dd-grid">
            {{-- Header Row: Arrows (Clickable) --}}
            @for($col = 0; $col < 4; $col++)
            <div class="arrow-cell" onclick="toggleArrow(this)" data-col="{{ $col }}" data-page="{{ $pageNumber }}">
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
                
                <div class="arrow-container" data-direction="{{ $showLeft ? 'left' : 'right' }}">
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
    
    <script>
        // SVG templates for arrows
        const leftArrowSVG = `<svg class="arrow-left" viewBox="0 0 100 30"><polygon points="0,15 20,0 20,10 100,10 100,20 20,20 20,30" fill="black"/></svg>`;
        const rightArrowSVG = `<svg class="arrow-right" viewBox="0 0 100 30"><polygon points="100,15 80,0 80,10 0,10 0,20 80,20 80,30" fill="black"/></svg>`;
        
        // Update QR Code size dynamically
        function updateQRSize(value) {
            document.documentElement.style.setProperty('--qr-size', value + 'mm');
            document.getElementById('qr-size-value').textContent = value + 'mm';
        }
        
        // Update Font size dynamically
        function updateFontSize(value) {
            document.documentElement.style.setProperty('--font-size', value + 'px');
            document.getElementById('font-size-value').textContent = value + 'px';
        }
        
        // Toggle individual arrow on click
        function toggleArrow(cell) {
            const container = cell.querySelector('.arrow-container');
            const currentDir = container.getAttribute('data-direction');
            const newDir = currentDir === 'left' ? 'right' : 'left';
            
            // Update direction
            container.setAttribute('data-direction', newDir);
            container.innerHTML = newDir === 'left' ? leftArrowSVG : rightArrowSVG;
        }
        
        // Set all arrows to a specific direction
        function setAllArrows(direction) {
            const containers = document.querySelectorAll('.arrow-container');
            
            containers.forEach((container, index) => {
                let newDir = direction;
                
                // For alternate mode, check column position
                if (direction === 'alternate') {
                    // Get parent arrow-cell and its column index
                    const cell = container.closest('.arrow-cell');
                    const col = parseInt(cell.getAttribute('data-col'));
                    newDir = (col % 2 === 0) ? 'left' : 'right';
                }
                
                container.setAttribute('data-direction', newDir);
                container.innerHTML = newDir === 'left' ? leftArrowSVG : rightArrowSVG;
            });
        }
    </script>
</body>

</html>
