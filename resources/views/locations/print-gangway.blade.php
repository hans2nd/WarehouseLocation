<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Lokasi Gangway</title>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
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
            width: var(--page-width, 210mm);
            height: var(--page-height, 291mm);
            padding: 0;
            margin: 0 auto;
            overflow: hidden;
        }

        /* Grid Container - 4 columns, full height */
        .gangway-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: var(--arrow-row-height, 14mm) repeat(5, 1fr);
            row-gap: var(--row-gap, 0px);
            border: var(--border-width, 0.1px) solid black;
            width: 100%;
            height: 100%;
        }

        /* Arrow Header Row */
        .arrow-cell {
            border: var(--border-width, 0.1px) solid black;
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
        
        /* Merged Arrow Cell - spans 2 columns */
        .arrow-cell-merged {
            grid-column: span 2;
        }



        .arrow-left, .arrow-right {
            width: var(--arrow-width, 20mm);
            height: var(--arrow-height, 7mm);
            transition: transform 0.3s ease;
        }
        
        /* Double arrow (2 panah) */
        .arrow-double-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 2mm;
        }
        
        .arrow-double-container svg {
            width: var(--arrow-width, 20mm);
            height: var(--arrow-height, 7mm);
        }

        /* QR Cell - fills available row height */
        .qr-cell {
            border: var(--border-width, 0.1px) solid black;
            padding: var(--cell-padding, 1.5mm);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 0;
        }

        .qr-cell.empty {
            background: white;
        }

        .qr-code svg {
            width: var(--qr-size, 30mm);
            height: var(--qr-size, 30mm);
        }

        .location-code {
            font-size: var(--font-size, 9px);
            font-weight: var(--font-weight, 700);
            margin-top: var(--label-gap, 1mm);
        }
        
        /* Floor Level Label */
        .floor-label {
            font-size: var(--floor-font-size, 10px);
            font-weight: var(--font-weight, 700);
            color: #333;
            margin-bottom: var(--label-gap, 1mm);
            text-transform: uppercase;
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
    <style id="page-orientation">
        @media print {
            @page {
                size: A4 portrait;
                margin: 3mm;
            }
        }
    </style>
</head>

<body>
    {{-- Floating Control Panel --}}
    <div class="control-panel no-print">
        <h3>⚙️ Pengaturan Cetak Gangway</h3>
        
        <div class="info-badge" id="orientation-badge">
            📐 A4 Portrait: 4 kolom × 5 baris = 20 lokasi/halaman
        </div>
        
        {{-- Section: Barcode per Halaman --}}
        <div class="control-section">
            <h4>📄 Barcode per Halaman</h4>
            <div class="slider-group">
                <select id="barcode-count-select" onchange="updateBarcodeCount(this.value)" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;font-size:13px;cursor:pointer">
                    <option value="20" selected>20 barcode (5 baris × 4 kolom - Portrait)</option>
                    <option value="16">16 barcode (4 baris × 4 kolom - Portrait)</option>
                    <option value="12p">12 barcode (3 baris × 4 kolom - Portrait)</option>
                    <option value="8p">8 barcode (2 baris × 4 kolom - Portrait)</option>
                </select>
            </div>
        </div>
        
        {{-- Section: Ukuran --}}
        <div class="control-section">
            <h4>📐 Ukuran</h4>
            <div class="slider-group">
                <label>QR Code: <span id="qr-size-value" class="slider-value">30mm</span></label>
                <input type="range" id="qr-size-slider" min="15" max="45" value="30" oninput="updateQRSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Font Lokasi: <span id="font-size-value" class="slider-value">9px</span></label>
                <input type="range" id="font-size-slider" min="6" max="24" value="9" oninput="updateFontSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Font Lantai: <span id="floor-font-size-value" class="slider-value">10px</span></label>
                <input type="range" id="floor-font-size-slider" min="6" max="24" value="10" oninput="updateFloorFontSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Jarak Label-Lokasi: <span id="label-gap-value" class="slider-value">1mm</span></label>
                <input type="range" id="label-gap-slider" min="0" max="50" value="10" oninput="updateLabelGap(this.value)">
            </div>
            <div class="slider-group">
                <label>Lebar Panah: <span id="arrow-width-value" class="slider-value">20mm</span></label>
                <input type="range" id="arrow-width-slider" min="10" max="40" value="20" oninput="updateArrowSize('width', this.value)">
            </div>
            <div class="slider-group">
                <label>Tinggi Panah: <span id="arrow-height-value" class="slider-value">7mm</span></label>
                <input type="range" id="arrow-height-slider" min="3" max="15" value="7" oninput="updateArrowSize('height', this.value)">
            </div>
            <div class="slider-group">
                <label>Ketebalan Huruf: <span id="font-weight-value" class="slider-value">700</span></label>
                <input type="range" id="font-weight-slider" min="100" max="900" step="100" value="700" oninput="updateFontWeight(this.value)">
            </div>
            <div class="slider-group">
                <label>Ketebalan Border: <span id="border-width-value" class="slider-value">0.1px</span></label>
                <input type="range" id="border-width-slider" min="1" max="30" value="1" oninput="updateBorderWidth(this.value)">
            </div>
            <button class="control-btn" id="toggle-border-btn" onclick="toggleBorder()">
                🚫 Hapus Border
            </button>
            <div class="slider-group" style="margin-top:8px">
                <label>Jarak Antar Baris: <span id="row-gap-value" class="slider-value">0mm</span></label>
                <input type="range" id="row-gap-slider" min="0" max="30" value="0" oninput="updateRowGap(this.value)">
            </div>
            <div class="slider-group">
                <label>Padding Cell: <span id="cell-padding-value" class="slider-value">1.5mm</span></label>
                <input type="range" id="cell-padding-slider" min="0" max="30" value="15" oninput="updateCellPadding(this.value)">
            </div>
        </div>
        
        {{-- Section: Label Lantai --}}
        <div class="control-section">
            <h4>🏢 Label Lantai</h4>
            <div class="slider-group">
                <label>Teks Label:</label>
                <input type="text" id="floor-label-text" value="LANTAI" class="text-input" oninput="updateFloorLabels()">
            </div>
        </div>
        
        {{-- Section: Arah Panah --}}
        <div class="control-section">
            <h4>🎯 Arah Panah</h4>
            <div class="slider-group" style="margin-bottom:10px">
                <label>Jenis Panah:</label>
                <select id="arrow-type-select" onchange="updateArrowType(this.value)" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;font-size:12px;cursor:pointer">
                    <option value="single" selected>1 Panah (← atau →)</option>
                    <option value="double-same">2 Panah Searah (←← atau →→)</option>
                    <option value="triple-same">3 Panah Searah (←←← atau →→→)</option>
                    <option value="double-oppose">2 Panah Berlawanan (← →)</option>
                </select>
            </div>
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
        
        // Parse locations
        // Format: ZONE.ROW.COLUMN.LEVEL (e.g., D.B.15.7, D.C.16.5)
        // Gangway layout:
        //   Grid ROW    = LEVEL (highest first: 7, 6, 5, 4, 3...)
        //   Grid COLUMN = ROW.COLUMN combos sorted by ROW then COLUMN
        //   Example: col1=D.B.15, col2=D.B.16, col3=D.C.15, col4=D.C.16
        //   Merged arrows: cols 1&2 (same ROW), cols 3&4 (same ROW)
        $parsed = [];
        
        foreach($locations as $loc) {
            $code = $loc->location_code;
            $segments = explode('.', $code);
            
            if (count($segments) >= 4) {
                $zone = $segments[0];            // D
                $row = $segments[1];             // B, C
                $column = (int)$segments[2];     // 15, 16
                
                // Check for sub-level (e.g., 1B3, 1A2)
                $subLevel = null;
                if (preg_match('/^(\d+)([A-Za-z]\d+)$/', $segments[3], $matches)) {
                    $level = (int)$matches[1];
                    $subLevel = strtoupper($matches[2]);
                } else {
                    $level = (int)$segments[3];
                }
                
                $parsed[] = [
                    'location' => $loc,
                    'zone' => $zone,
                    'row' => $row,
                    'column' => $column,
                    'level' => $level,
                    'subLevel' => $subLevel,
                    'rcKey' => "{$row}.{$column}",  // ROW.COLUMN combo key
                ];
            }
        }
        
        // Group by ZONE
        $zoneGroups = [];
        foreach ($parsed as $item) {
            $zoneGroups[$item['zone']][] = $item;
        }
        ksort($zoneGroups, SORT_NATURAL);
        
        // Build pages
        $rowsPerPage = 5;
        $pages = [];
        
        foreach ($zoneGroups as $zone => $items) {
            // Get unique ROW.COLUMN combos, sorted by ROW (natural) then COLUMN (numeric)
            $rcCombos = [];
            foreach ($items as $item) {
                $rcCombos[$item['rcKey']] = ['row' => $item['row'], 'column' => $item['column']];
            }
            
            // Sort: by ROW naturally, then by COLUMN numerically
            uasort($rcCombos, function($a, $b) {
                $cmp = strnatcmp($a['row'], $b['row']);
                if ($cmp !== 0) return $cmp;
                return $a['column'] - $b['column'];
            });
            
            $rcKeys = array_keys($rcCombos); // e.g., ['B.15', 'B.16', 'C.15', 'C.16']
            
            // Index items by rcKey + level + subLevel for lookup
            $indexed = [];
            foreach ($items as $item) {
                $indexed[$item['rcKey']][$item['level']][$item['subLevel']] = $item;
            }
            
            // Chunk rcKeys into sets of 4 (= 4 grid columns)
            $rcSets = array_chunk($rcKeys, 4);
            
            foreach ($rcSets as $rcSet) {
                // Find max level across all rcKeys in this set
                $setMaxLevel = 0;
                foreach ($rcSet as $rck) {
                    if (isset($indexed[$rck])) {
                        $setMaxLevel = max($setMaxLevel, max(array_keys($indexed[$rck])));
                    }
                }
                
                // Create grid rows from highest level to lowest
                $setRows = [];
                for ($level = $setMaxLevel; $level >= 1; $level--) {
                    // Check for sub-levels at this level
                    $allSubLevels = [];
                    foreach ($rcSet as $rck) {
                        if (isset($indexed[$rck][$level])) {
                            foreach (array_keys($indexed[$rck][$level]) as $sl) {
                                if ($sl !== null && $sl !== '' && !in_array($sl, $allSubLevels)) {
                                    $allSubLevels[] = $sl;
                                }
                            }
                        }
                    }
                    
                    if (!empty($allSubLevels)) {
                        rsort($allSubLevels, SORT_NATURAL);
                        foreach ($allSubLevels as $sl) {
                            $gridRow = ['level' => $level, 'subLevel' => $sl];
                            for ($i = 0; $i < 4; $i++) {
                                $rck = $rcSet[$i] ?? null;
                                $gridRow['col' . ($i + 1)] = ($rck !== null && isset($indexed[$rck][$level][$sl]))
                                    ? $indexed[$rck][$level][$sl]
                                    : null;
                            }
                            $setRows[] = $gridRow;
                        }
                        // Also check null sub-level
                        $hasNormal = false;
                        foreach ($rcSet as $rck) {
                            if (isset($indexed[$rck][$level][null])) { $hasNormal = true; break; }
                        }
                        if ($hasNormal) {
                            $gridRow = ['level' => $level, 'subLevel' => null];
                            for ($i = 0; $i < 4; $i++) {
                                $rck = $rcSet[$i] ?? null;
                                $gridRow['col' . ($i + 1)] = ($rck !== null && isset($indexed[$rck][$level][null]))
                                    ? $indexed[$rck][$level][null] : null;
                            }
                            $setRows[] = $gridRow;
                        }
                    } else {
                        // Normal (no sub-levels)
                        $gridRow = ['level' => $level, 'subLevel' => null];
                        for ($i = 0; $i < 4; $i++) {
                            $rck = $rcSet[$i] ?? null;
                            $gridRow['col' . ($i + 1)] = ($rck !== null && isset($indexed[$rck][$level][null]))
                                ? $indexed[$rck][$level][null] : null;
                        }
                        $setRows[] = $gridRow;
                    }
                }
                
                // Paginate
                $setPages = array_chunk($setRows, $rowsPerPage);
                foreach ($setPages as $page) {
                    $pages[] = $page;
                }
            }
        }
        
        $pageNumber = 0;
    @endphp

    <div id="pages-container">
    @foreach($pages as $pageIndex => $pageRows)
    @php
        $pageNumber++;
    @endphp
    
    <div class="page {{ !$loop->last ? 'page-break' : '' }}">
        
        <div class="gangway-grid">
            {{-- Header Row: Merged Arrows (col 1&2 and col 3&4) --}}
            @for($pair = 0; $pair < 2; $pair++)
                @php
                    // Determine arrow direction for this pair
                    if ($arrowDir === 'left') {
                        $showLeft = true;
                    } elseif ($arrowDir === 'right') {
                        $showLeft = false;
                    } else {
                        // Alternate: pair 0 = left, pair 1 = right
                        $showLeft = ($pair % 2 == 0);
                    }
                @endphp
                <div class="arrow-cell arrow-cell-merged" onclick="toggleArrow(this)" data-col="{{ $pair }}" data-page="{{ $pageNumber }}">
                    <div class="arrow-container" data-direction="{{ $showLeft ? 'left' : 'right' }}">
                        @if($showLeft)
                        <svg class="arrow-left" viewBox="0 0 100 30"><polygon points="0,15 20,0 20,10 100,10 100,20 20,20 20,30" fill="black"/></svg>
                        @else
                        <svg class="arrow-right" viewBox="0 0 100 30"><polygon points="100,15 80,0 80,10 0,10 0,20 80,20 80,30" fill="black"/></svg>
                        @endif
                    </div>
                </div>
            @endfor

            {{-- QR Code Rows (5 rows per page) --}}
            @foreach($pageRows as $rowData)
                @for($c = 1; $c <= 4; $c++)
                    @php $item = $rowData['col' . $c] ?? null; @endphp
                    <div class="qr-cell {{ !$item ? 'empty' : '' }}">
                        @if($item)
                        <span class="floor-label" data-floor="{{ $item['level'] ?? '' }}" data-sublevel="{{ $item['subLevel'] ?? '' }}">LANTAI {{ $item['level'] ?? '' }}{{ $item['subLevel'] ? ' ' . $item['subLevel'] : '' }}</span>
                        <div class="qr-code">
                            {!! QrCode::size(80)->generate($item['location']->location_code) !!}
                        </div>
                        <span class="location-code">{{ $item['location']->location_code }}</span>
                        @endif
                    </div>
                @endfor
            @endforeach
        </div>
    </div>
    @endforeach
    </div>
    
    <script>
        // SVG templates for arrows
        const leftArrowSVG = `<svg class="arrow-left" viewBox="0 0 100 30"><polygon points="0,15 20,0 20,10 100,10 100,20 20,20 20,30" fill="black"/></svg>`;
        const rightArrowSVG = `<svg class="arrow-right" viewBox="0 0 100 30"><polygon points="100,15 80,0 80,10 0,10 0,20 80,20 80,30" fill="black"/></svg>`;
        
        // Arrow type state
        let currentArrowType = 'single';
        
        // Build arrow HTML from direction + count + oppose flag
        function buildArrowHTML(direction, count, oppose) {
            if (count === 1) {
                return direction === 'left' ? leftArrowSVG : rightArrowSVG;
            }
            let arrows = '';
            for (let i = 0; i < count; i++) {
                if (oppose && i > 0) {
                    arrows += (direction === 'left' ? rightArrowSVG : leftArrowSVG);
                } else {
                    arrows += (direction === 'left' ? leftArrowSVG : rightArrowSVG);
                }
            }
            return '<div class="arrow-double-container">' + arrows + '</div>';
        }
        
        // Get arrow HTML based on type + direction
        function getArrowHTML(type, direction) {
            if (type === 'single') return buildArrowHTML(direction, 1, false);
            if (type === 'double-same') return buildArrowHTML(direction, 2, false);
            if (type === 'triple-same') return buildArrowHTML(direction, 3, false);
            if (type === 'double-oppose') return buildArrowHTML(direction, 2, true);
            return buildArrowHTML(direction, 1, false);
        }
        
        // Get arrow count from type
        function getCountFromType(type) {
            if (type === 'double-same' || type === 'double-oppose') return 2;
            if (type === 'triple-same') return 3;
            return 1;
        }
        
        // Dynamic barcode count per page
        let currentRowsPerPage = 5;
        let originalPagesHTML = null;
        
        // Save original structure on load
        document.addEventListener('DOMContentLoaded', function() {
            originalPagesHTML = document.getElementById('pages-container').innerHTML;
        });
        
        function updateBarcodeCount(count) {
            count = String(count);
            let cols = 4;
            let rowsPerPage;
            let numericCount;
            let isPortrait = true; // Gangway always portrait
            
            if (count === '12p') {
                rowsPerPage = 3;
                numericCount = 12;
            } else if (count === '8p') {
                rowsPerPage = 2;
                numericCount = 8;
            } else {
                numericCount = parseInt(count);
                rowsPerPage = numericCount / 4;
            }
            
            currentRowsPerPage = rowsPerPage;
            const badge = document.getElementById('orientation-badge');
            const styleEl = document.getElementById('page-orientation');
            
            document.documentElement.style.setProperty('--page-width', '210mm');
            document.documentElement.style.setProperty('--page-height', '291mm');
            styleEl.textContent = '@media print { @page { size: A4 portrait; margin: 3mm; } }';
            badge.innerHTML = '\ud83d\udcd0 A4 Portrait: ' + cols + ' kolom \u00d7 ' + rowsPerPage + ' baris = ' + numericCount + ' lokasi/halaman';
            
            restructurePages(rowsPerPage, cols, numericCount);
        }
        
        function restructurePages(rowsPerPage, cols, itemsPerPage) {
            cols = cols || 4;
            itemsPerPage = itemsPerPage || (rowsPerPage * cols);
            const gridCapacity = rowsPerPage * cols;
            const container = document.getElementById('pages-container');
            
            // Restore original HTML first
            container.innerHTML = originalPagesHTML;
            
            const originalPages = container.querySelectorAll('.page');
            
            // Collect ALL QR cells from all pages (flatten) - gangway is continuous
            const allCells = [];
            const allArrows = [];
            
            originalPages.forEach((page, idx) => {
                const grid = page.querySelector('.gangway-grid');
                if (idx === 0) {
                    // Grab arrows from first page as template
                    allArrows.push(...Array.from(grid.querySelectorAll('.arrow-cell')).map(ac => ac.cloneNode(true)));
                }
                const cells = Array.from(grid.querySelectorAll('.qr-cell'));
                cells.forEach(c => {
                    // Only include non-empty cells
                    if (!c.classList.contains('empty')) {
                        allCells.push(c.cloneNode(true));
                    }
                });
            });
            
            // Re-chunk all cells into pages
            const allChunks = [];
            for (let i = 0; i < allCells.length; i += itemsPerPage) {
                allChunks.push(allCells.slice(i, i + itemsPerPage));
            }
            
            // If no chunks, create at least one empty page
            if (allChunks.length === 0) {
                allChunks.push([]);
            }
            
            // Helper: create merged arrow cells (pairs of 2 columns)
            function createArrowCells(sourceArrows) {
                const arrowCells = [];
                const numPairs = 2; // Always 2 merged arrow pairs for 4 columns
                for (let p = 0; p < numPairs; p++) {
                    if (p < sourceArrows.length) {
                        const ac = sourceArrows[p].cloneNode(true);
                        ac.setAttribute('data-col', p);
                        const container = ac.querySelector('.arrow-container');
                        if (container) {
                            const dir = container.getAttribute('data-direction') || ((p % 2 === 0) ? 'left' : 'right');
                            container.setAttribute('data-count', getCountFromType(currentArrowType));
                            container.innerHTML = getArrowHTML(currentArrowType, dir);
                        }
                        arrowCells.push(ac);
                    } else {
                        const ac = document.createElement('div');
                        ac.className = 'arrow-cell arrow-cell-merged';
                        ac.setAttribute('data-col', p);
                        ac.setAttribute('onclick', 'toggleArrow(this)');
                        const dir = (p % 2 === 0) ? 'left' : 'right';
                        ac.innerHTML = '<div class="arrow-container" data-direction="' + dir + '" data-count="' + getCountFromType(currentArrowType) + '">' + getArrowHTML(currentArrowType, dir) + '</div>';
                        arrowCells.push(ac);
                    }
                }
                return arrowCells;
            }
            
            // Build pages
            container.innerHTML = '';
            
            allChunks.forEach((chunk, idx) => {
                const pageDiv = document.createElement('div');
                pageDiv.className = 'page' + (idx < allChunks.length - 1 ? ' page-break' : '');
                
                const gridDiv = document.createElement('div');
                gridDiv.className = 'gangway-grid';
                gridDiv.style.gridTemplateColumns = 'repeat(' + cols + ', 1fr)';
                gridDiv.style.gridTemplateRows = 'var(--arrow-row-height, 14mm) repeat(' + rowsPerPage + ', 1fr)';
                
                // Add merged arrow cells
                const arrowCells = createArrowCells(allArrows);
                arrowCells.forEach(ac => gridDiv.appendChild(ac));
                
                // Add QR cells
                chunk.forEach(cell => gridDiv.appendChild(cell));
                
                // Pad with empty cells
                const remaining = gridCapacity - chunk.length;
                for (let p = 0; p < remaining; p++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'qr-cell empty';
                    gridDiv.appendChild(emptyCell);
                }
                
                pageDiv.appendChild(gridDiv);
                container.appendChild(pageDiv);
            });
        }
        
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
        
        // Update Label Gap dynamically
        function updateLabelGap(value) {
            const mm = (value / 10).toFixed(1);
            document.documentElement.style.setProperty('--label-gap', mm + 'mm');
            document.getElementById('label-gap-value').textContent = mm + 'mm';
        }
        
        // Update Row Gap dynamically
        function updateRowGap(value) {
            const mm = (value / 10).toFixed(1);
            document.documentElement.style.setProperty('--row-gap', mm + 'mm');
            document.getElementById('row-gap-value').textContent = mm + 'mm';
        }
        
        // Update Cell Padding dynamically
        function updateCellPadding(value) {
            const mm = (value / 10).toFixed(1);
            document.documentElement.style.setProperty('--cell-padding', mm + 'mm');
            document.getElementById('cell-padding-value').textContent = mm + 'mm';
        }
        
        // Update Arrow size dynamically (width or height)
        function updateArrowSize(dimension, value) {
            document.documentElement.style.setProperty('--arrow-' + dimension, value + 'mm');
            document.getElementById('arrow-' + dimension + '-value').textContent = value + 'mm';
        }
        
        // Update Font Weight dynamically
        function updateFontWeight(value) {
            document.documentElement.style.setProperty('--font-weight', value);
            document.getElementById('font-weight-value').textContent = value;
        }
        
        // Update Border Width dynamically
        function updateBorderWidth(value) {
            const px = (value / 10).toFixed(1);
            document.documentElement.style.setProperty('--border-width', px + 'px');
            document.getElementById('border-width-value').textContent = px + 'px';
        }
        
        // Toggle Border on/off
        let borderVisible = true;
        function toggleBorder() {
            borderVisible = !borderVisible;
            const btn = document.getElementById('toggle-border-btn');
            if (borderVisible) {
                const sliderVal = document.getElementById('border-width-slider').value;
                const px = (sliderVal / 10).toFixed(1);
                document.documentElement.style.setProperty('--border-width', px + 'px');
                document.getElementById('border-width-value').textContent = px + 'px';
                btn.innerHTML = '🚫 Hapus Border';
            } else {
                document.documentElement.style.setProperty('--border-width', '0px');
                document.getElementById('border-width-value').textContent = '0px';
                btn.innerHTML = '✅ Tampilkan Border';
            }
        }
        
        // Update Floor Labels text
        function updateFloorLabels() {
            const labelText = document.getElementById('floor-label-text').value || 'LANTAI';
            const floorLabels = document.querySelectorAll('.floor-label');
            
            floorLabels.forEach(label => {
                const floorNumber = label.getAttribute('data-floor');
                const subLevel = label.getAttribute('data-sublevel');
                let text = labelText + ' ' + floorNumber;
                if (subLevel) {
                    text += ' ' + subLevel;
                }
                label.textContent = text;
            });
        }
        
        // Toggle individual arrow on click
        function toggleArrow(cell) {
            const container = cell.querySelector('.arrow-container');
            const currentDir = container.getAttribute('data-direction') || 'left';
            const currentCount = parseInt(container.getAttribute('data-count') || '1');
            
            let newDir, newCount;
            if (currentDir === 'left') {
                newDir = 'right';
                newCount = currentCount;
            } else {
                newDir = 'left';
                newCount = currentCount >= 3 ? 1 : currentCount + 1;
            }
            
            container.setAttribute('data-direction', newDir);
            container.setAttribute('data-count', newCount);
            container.innerHTML = buildArrowHTML(newDir, newCount, false);
        }
        
        // Set all arrows to a specific direction
        function setAllArrows(direction) {
            const containers = document.querySelectorAll('.arrow-container');
            const count = getCountFromType(currentArrowType);
            const oppose = currentArrowType === 'double-oppose';
            
            containers.forEach((container, index) => {
                let newDir = direction;
                
                if (direction === 'alternate') {
                    const cell = container.closest('.arrow-cell');
                    const col = parseInt(cell.getAttribute('data-col'));
                    newDir = (col % 2 === 0) ? 'left' : 'right';
                }
                
                container.setAttribute('data-direction', newDir);
                container.setAttribute('data-count', count);
                container.innerHTML = buildArrowHTML(newDir, count, oppose);
            });
        }
        
        // Update arrow type
        function updateArrowType(type) {
            currentArrowType = type;
            const count = getCountFromType(type);
            const oppose = type === 'double-oppose';
            const containers = document.querySelectorAll('.arrow-container');
            containers.forEach(container => {
                const dir = container.getAttribute('data-direction') || 'left';
                container.setAttribute('data-count', count);
                container.innerHTML = buildArrowHTML(dir, count, oppose);
            });
        }
    </script>
</body>

</html>
