<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Lokasi Double Deep</title>
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 3mm;
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
            padding: 2mm;
            margin: 0 auto;
        }

        /* Grid Container - 4 columns for double deep (2 columns) */
        .dd-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border: 1px solid black;
            width: 100%;
        }

        /* Arrow Header Row */
        .arrow-cell {
            border: 1px solid black;
            padding: 1.5mm;
            text-align: center;
            height: 14mm;
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
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 0.5mm;
        }

        .arrow-left, .arrow-right {
            width: 20mm;
            height: 7mm;
            transition: transform 0.3s ease;
        }

        /* QR Cell - Compact untuk 5 rows per page */
        .qr-cell {
            border: 1px solid black;
            padding: 1.5mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 50mm;
        }

        .qr-cell.empty {
            background: white;
        }

        .qr-code svg {
            width: var(--qr-size, 22mm);
            height: var(--qr-size, 22mm);
        }

        .location-code {
            font-size: var(--font-size, 9px);
            font-weight: bold;
            margin-top: 1mm;
        }
        
        /* Floor Level Label - LUAR/DALAM */
        .floor-label {
            font-size: var(--floor-font-size, 9px);
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5mm;
            text-transform: uppercase;
            text-align: center;
            line-height: 1.2;
        }
        
        .floor-label .level-number {
            display: block;
        }
        
        .floor-label .position-type {
            display: block;
            font-size: 0.9em;
        }

        /* Footer Row */
        .footer-cell {
            border: 1px solid black;
            padding: 1.5mm;
            text-align: center;
            height: 6mm;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #e5e7eb;
        }

        .footer-marker {
            font-size: 10px;
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
            min-width: 220px;
            max-height: 90vh;
            overflow-y: auto;
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
        
        .text-input {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            margin-top: 4px;
        }
        
        .text-input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .info-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 11px;
            margin-bottom: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    {{-- Floating Control Panel --}}
    <div class="control-panel no-print">
        <h3>⚙️ Pengaturan Cetak Double Deep</h3>
        
        <div class="info-badge">
            📐 A4: 4 kolom × 5 baris = 20 lokasi/halaman
        </div>
        
        {{-- Section: Ukuran --}}
        <div class="control-section">
            <h4>📐 Ukuran</h4>
            <div class="slider-group">
                <label>QR Code: <span id="qr-size-value" class="slider-value">22mm</span></label>
                <input type="range" id="qr-size-slider" min="15" max="32" value="22" oninput="updateQRSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Font Lokasi: <span id="font-size-value" class="slider-value">9px</span></label>
                <input type="range" id="font-size-slider" min="6" max="14" value="9" oninput="updateFontSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Font Label: <span id="floor-font-size-value" class="slider-value">9px</span></label>
                <input type="range" id="floor-font-size-slider" min="6" max="14" value="9" oninput="updateFloorFontSize(this.value)">
            </div>
        </div>
        
        {{-- Section: Label --}}
        <div class="control-section">
            <h4>🏢 Label Teks</h4>
            <div class="slider-group">
                <label>Teks Level:</label>
                <input type="text" id="level-label-text" value="LANTAI" class="text-input" oninput="updateAllLabels()">
            </div>
            <div class="slider-group">
                <label>Teks Luar:</label>
                <input type="text" id="outer-label-text" value="LUAR" class="text-input" oninput="updateAllLabels()">
            </div>
            <div class="slider-group">
                <label>Teks Dalam:</label>
                <input type="text" id="inner-label-text" value="DALAM" class="text-input" oninput="updateAllLabels()">
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
        
        // Parse and group locations
        // Format: ZONE.ROW.COLUMN.LEVEL.DEPTH (e.g., FA.A.1.1.a, FA.A.2.5.b)
        // ZONE = FA, ROW = A, COLUMN = 1, LEVEL = 1, DEPTH = a/b
        $parsed = [];
        
        foreach($locations as $loc) {
            $code = $loc->location_code;
            $segments = explode('.', $code);
            
            // Support both 5-segment (FA.A.1.1.a) and 4-segment (AC.1.1.1a) formats
            if (count($segments) >= 5) {
                // 5-segment format: ZONE.ROW.COLUMN.LEVEL.DEPTH
                $zone = $segments[0];            // FA
                $row = $segments[1];             // A
                $column = (int)$segments[2];     // 1, 2, etc.
                $level = (int)$segments[3];      // 1, 2, 3, 4, 5
                $depth = strtolower($segments[4]); // a = LUAR, b = DALAM
                
                // Group key: ZONE.ROW (e.g., FA.A)
                $groupKey = "{$zone}.{$row}";
                
                $parsed[] = [
                    'location' => $loc,
                    'groupKey' => $groupKey,
                    'column' => $column,
                    'level' => $level,
                    'depth' => $depth,
                ];
            } elseif (count($segments) >= 4) {
                // 4-segment format: PREFIX.ZONE.BAY.LEVELdepth (e.g., AC.1.1.5a)
                $prefix = $segments[0];          // AC
                $zone = $segments[1];            // 1
                $column = (int)$segments[2];     // 1, 2
                $levelDepth = $segments[3];      // 5a, 5b
                
                preg_match('/(\d+)([ab])?/i', $levelDepth, $matches);
                $level = (int)($matches[1] ?? 1);
                $depth = strtolower($matches[2] ?? 'a');
                
                $groupKey = "{$prefix}.{$zone}";
                
                $parsed[] = [
                    'location' => $loc,
                    'groupKey' => $groupKey,
                    'column' => $column,
                    'level' => $level,
                    'depth' => $depth,
                ];
            }
        }
        
        // Group by groupKey (ZONE.ROW)
        $groups = [];
        foreach ($parsed as $item) {
            $groups[$item['groupKey']][] = $item;
        }
        
        // Sort groups naturally
        ksort($groups, SORT_NATURAL);
        
        // Build pages for each group
        // Layout: Column pairs (1,2), (3,4), etc. with levels from high to low
        // Each column pair gets its own pages (no mixing column pairs on the same page)
        $rowsPerPage = 5;
        $pages = [];
        
        foreach ($groups as $groupKey => $items) {
            // Get unique columns and sort them
            $columns = array_unique(array_column($items, 'column'));
            sort($columns, SORT_NUMERIC);
            
            // Pair consecutive columns: (1,2), (3,4), (5,6), etc.
            $columnPairs = [];
            for ($i = 0; $i < count($columns); $i += 2) {
                $col1 = $columns[$i] ?? null;
                $col2 = $columns[$i + 1] ?? null;
                if ($col1 !== null) {
                    $columnPairs[] = [$col1, $col2];
                }
            }
            
            // Index items by column, level, depth for quick lookup
            $indexed = [];
            foreach ($items as $item) {
                $indexed[$item['column']][$item['level']][$item['depth']] = $item;
            }
            
            // For each column pair, create rows and paginate independently
            foreach ($columnPairs as $pair) {
                $col1 = $pair[0];
                $col2 = $pair[1];
                
                // Find max level for this specific column pair
                $pairMaxLevel = 0;
                if ($col1 !== null && isset($indexed[$col1])) {
                    $pairMaxLevel = max($pairMaxLevel, max(array_keys($indexed[$col1])));
                }
                if ($col2 !== null && isset($indexed[$col2])) {
                    $pairMaxLevel = max($pairMaxLevel, max(array_keys($indexed[$col2])));
                }
                
                // Create rows from highest level to lowest (top to bottom in print)
                $pairRows = [];
                for ($level = $pairMaxLevel; $level >= 1; $level--) {
                    $pairRows[] = [
                        'level' => $level,
                        // Column 1: Col1 Depth A (LUAR)
                        'col1' => $indexed[$col1][$level]['a'] ?? null,
                        // Column 2: Col1 Depth B (DALAM)
                        'col2' => $indexed[$col1][$level]['b'] ?? null,
                        // Column 3: Col2 Depth A (LUAR)
                        'col3' => ($col2 !== null) ? ($indexed[$col2][$level]['a'] ?? null) : null,
                        // Column 4: Col2 Depth B (DALAM)
                        'col4' => ($col2 !== null) ? ($indexed[$col2][$level]['b'] ?? null) : null,
                    ];
                }
                
                // Paginate this column pair independently
                $pairPages = array_chunk($pairRows, $rowsPerPage);
                foreach ($pairPages as $page) {
                    $pages[] = $page;
                }
            }
        }
        
        $pageNumber = 0;
    @endphp

    @foreach($pages as $pageIndex => $pageRows)
    @php
        $pageNumber++;
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

            {{-- QR Code Rows (5 rows per page) --}}
            @foreach($pageRows as $rowData)
                {{-- Column 1: Col1 LUAR (a) --}}
                <div class="qr-cell {{ !$rowData['col1'] ? 'empty' : '' }}">
                    @if($rowData['col1'])
                    <span class="floor-label" data-level="{{ $rowData['level'] }}" data-position="a">
                        <span class="level-number">LANTAI {{ $rowData['level'] }}</span>
                        <span class="position-type">LUAR</span>
                    </span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($rowData['col1']['location']->location_code) !!}
                    </div>
                    <span class="location-code">{{ $rowData['col1']['location']->location_code }}</span>
                    @endif
                </div>
                
                {{-- Column 2: Col1 DALAM (b) --}}
                <div class="qr-cell {{ !$rowData['col2'] ? 'empty' : '' }}">
                    @if($rowData['col2'])
                    <span class="floor-label" data-level="{{ $rowData['level'] }}" data-position="b">
                        <span class="level-number">LANTAI {{ $rowData['level'] }}</span>
                        <span class="position-type">DALAM</span>
                    </span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($rowData['col2']['location']->location_code) !!}
                    </div>
                    <span class="location-code">{{ $rowData['col2']['location']->location_code }}</span>
                    @endif
                </div>
                
                {{-- Column 3: Col2 LUAR (a) --}}
                <div class="qr-cell {{ !$rowData['col3'] ? 'empty' : '' }}">
                    @if($rowData['col3'])
                    <span class="floor-label" data-level="{{ $rowData['level'] }}" data-position="a">
                        <span class="level-number">LANTAI {{ $rowData['level'] }}</span>
                        <span class="position-type">LUAR</span>
                    </span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($rowData['col3']['location']->location_code) !!}
                    </div>
                    <span class="location-code">{{ $rowData['col3']['location']->location_code }}</span>
                    @endif
                </div>
                
                {{-- Column 4: Col2 DALAM (b) --}}
                <div class="qr-cell {{ !$rowData['col4'] ? 'empty' : '' }}">
                    @if($rowData['col4'])
                    <span class="floor-label" data-level="{{ $rowData['level'] }}" data-position="b">
                        <span class="level-number">LANTAI {{ $rowData['level'] }}</span>
                        <span class="position-type">DALAM</span>
                    </span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($rowData['col4']['location']->location_code) !!}
                    </div>
                    <span class="location-code">{{ $rowData['col4']['location']->location_code }}</span>
                    @endif
                </div>
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
        
        // Update Floor Font size dynamically
        function updateFloorFontSize(value) {
            document.documentElement.style.setProperty('--floor-font-size', value + 'px');
            document.getElementById('floor-font-size-value').textContent = value + 'px';
        }
        
        // Update all labels (level text, outer text, inner text)
        function updateAllLabels() {
            const levelText = document.getElementById('level-label-text').value || 'LANTAI';
            const outerText = document.getElementById('outer-label-text').value || 'LUAR';
            const innerText = document.getElementById('inner-label-text').value || 'DALAM';
            
            const floorLabels = document.querySelectorAll('.floor-label');
            
            floorLabels.forEach(label => {
                const level = label.getAttribute('data-level');
                const position = label.getAttribute('data-position');
                
                const levelSpan = label.querySelector('.level-number');
                const positionSpan = label.querySelector('.position-type');
                
                if (levelSpan) {
                    levelSpan.textContent = levelText + ' ' + level;
                }
                if (positionSpan) {
                    positionSpan.textContent = position === 'a' ? outerText : innerText;
                }
            });
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
