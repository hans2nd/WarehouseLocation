<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Lokasi Racking</title>
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
        .rack-grid {
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



        .arrow-left, .arrow-right {
            width: var(--arrow-width, 20mm);
            height: var(--arrow-height, 7mm);
            transition: transform 0.3s ease;
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
        <h3>⚙️ Pengaturan Cetak Racking</h3>
        
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
                    <option value="12">12 barcode (3 baris × 4 kolom - Landscape)</option>
                    <option value="8">8 barcode (2 baris × 4 kolom - Landscape)</option>
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
        // Format: ZONE.ROW.COLUMN.LEVEL (e.g., D.A.1.1, D.A.1.3)
        // Ground Shelving: ZONE.ROW.COLUMN.LEVELBn (e.g., D.K.1.1B3, D.K.1.1B2, D.K.1.1B1)
        $parsed = [];
        
        foreach($locations as $loc) {
            $code = $loc->location_code;
            $segments = explode('.', $code);
            
            if (count($segments) >= 4) {
                $zone = $segments[0];            // D
                $row = $segments[1];             // K
                $column = (int)$segments[2];     // 1, 2, etc.
                
                // Check for ground shelving sub-level (e.g., 1A3, 1B2, 1C1)
                $subLevel = null;
                if (preg_match('/^(\d+)([A-Za-z]\d+)$/', $segments[3], $matches)) {
                    $level = (int)$matches[1];           // 1
                    $subLevel = strtoupper($matches[2]); // A3, B2, C1, etc.
                } else {
                    $level = (int)$segments[3];           // 1, 2, 3, 4, 5
                }
                
                // Group key: ZONE.ROW (e.g., D.A)
                $groupKey = "{$zone}.{$row}";
                
                $parsed[] = [
                    'location' => $loc,
                    'groupKey' => $groupKey,
                    'column' => $column,
                    'level' => $level,
                    'subLevel' => $subLevel,
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
        // Layout: 4 columns per row, arranged by level from high to low
        // Ground shelving sub-levels create extra rows within their floor level
        // Each column set gets its own pages (no mixing column sets on the same page)
        $rowsPerPage = 5;
        $pages = [];
        
        foreach ($groups as $groupKey => $items) {
            // Get unique columns and sort them
            $columns = array_unique(array_column($items, 'column'));
            sort($columns, SORT_NUMERIC);
            
            // Group columns in sets of 4 for each page row
            $columnSets = array_chunk($columns, 4);
            
            // Index items by column, level, subLevel for quick lookup
            // Normal: $indexed[$col][$level][null] = $item
            // Ground shelving: $indexed[$col][$level]['B3'] = $item
            $indexed = [];
            foreach ($items as $item) {
                $indexed[$item['column']][$item['level']][$item['subLevel']] = $item;
            }
            
            // For each column set, create rows and paginate independently
            foreach ($columnSets as $columnSet) {
                // Find max level for this specific column set
                $setMaxLevel = 0;
                foreach ($columnSet as $col) {
                    if (isset($indexed[$col])) {
                        $setMaxLevel = max($setMaxLevel, max(array_keys($indexed[$col])));
                    }
                }
                
                // Create rows from highest level to lowest (top to bottom in print)
                $setRows = [];
                for ($level = $setMaxLevel; $level >= 1; $level--) {
                    // Collect all sub-levels across all columns in this set for this level
                    $allSubLevels = [];
                    foreach ($columnSet as $col) {
                        if (isset($indexed[$col][$level])) {
                            foreach (array_keys($indexed[$col][$level]) as $sl) {
                                if ($sl !== null && $sl !== '' && !in_array($sl, $allSubLevels)) {
                                    $allSubLevels[] = $sl;
                                }
                            }
                        }
                    }
                    
                    if (!empty($allSubLevels)) {
                        // Ground shelving: sort sub-levels descending (B3, B2, B1)
                        rsort($allSubLevels, SORT_NATURAL);
                        
                        foreach ($allSubLevels as $sl) {
                            $row = ['level' => $level, 'subLevel' => $sl];
                            for ($i = 0; $i < 4; $i++) {
                                $col = $columnSet[$i] ?? null;
                                $row['col' . ($i + 1)] = ($col !== null && isset($indexed[$col][$level][$sl])) 
                                    ? $indexed[$col][$level][$sl] 
                                    : null;
                            }
                            $setRows[] = $row;
                        }
                        
                        // Also check if there are normal items (null sub-level) at this level
                        $hasNormal = false;
                        foreach ($columnSet as $col) {
                            if (isset($indexed[$col][$level][null])) {
                                $hasNormal = true;
                                break;
                            }
                        }
                        if ($hasNormal) {
                            $row = ['level' => $level, 'subLevel' => null];
                            for ($i = 0; $i < 4; $i++) {
                                $col = $columnSet[$i] ?? null;
                                $row['col' . ($i + 1)] = ($col !== null && isset($indexed[$col][$level][null])) 
                                    ? $indexed[$col][$level][null] 
                                    : null;
                            }
                            $setRows[] = $row;
                        }
                    } else {
                        // Normal racking (no sub-levels at this level)
                        $row = ['level' => $level, 'subLevel' => null];
                        for ($i = 0; $i < 4; $i++) {
                            $col = $columnSet[$i] ?? null;
                            $row['col' . ($i + 1)] = ($col !== null && isset($indexed[$col][$level][null])) 
                                ? $indexed[$col][$level][null] 
                                : null;
                        }
                        $setRows[] = $row;
                    }
                }
                
                // Paginate this column set independently
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
        
        <div class="rack-grid">
            {{-- Header Row: Arrows (Clickable) --}}
            @for($col = 0; $col < 4; $col++)
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
                <x-arrow-indicator :direction="$showLeft ? 'left' : 'right'" :col="$col" :page="$pageNumber" />
            @endfor

            {{-- QR Code Rows (5 rows per page) --}}
            @foreach($pageRows as $rowData)
                {{-- Column 1 --}}
                <div class="qr-cell {{ !$rowData['col1'] ? 'empty' : '' }}">
                    @if($rowData['col1'])
                    <span class="floor-label" data-floor="{{ $rowData['level'] }}" data-sublevel="{{ $rowData['subLevel'] ?? '' }}">LANTAI {{ $rowData['level'] }}{{ $rowData['subLevel'] ? ' ' . $rowData['subLevel'] : '' }}</span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($rowData['col1']['location']->location_code) !!}
                    </div>
                    <span class="location-code">{{ $rowData['col1']['location']->location_code }}</span>
                    @endif
                </div>
                
                {{-- Column 2 --}}
                <div class="qr-cell {{ !$rowData['col2'] ? 'empty' : '' }}">
                    @if($rowData['col2'])
                    <span class="floor-label" data-floor="{{ $rowData['level'] }}" data-sublevel="{{ $rowData['subLevel'] ?? '' }}">LANTAI {{ $rowData['level'] }}{{ $rowData['subLevel'] ? ' ' . $rowData['subLevel'] : '' }}</span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($rowData['col2']['location']->location_code) !!}
                    </div>
                    <span class="location-code">{{ $rowData['col2']['location']->location_code }}</span>
                    @endif
                </div>
                
                {{-- Column 3 --}}
                <div class="qr-cell {{ !$rowData['col3'] ? 'empty' : '' }}">
                    @if($rowData['col3'])
                    <span class="floor-label" data-floor="{{ $rowData['level'] }}" data-sublevel="{{ $rowData['subLevel'] ?? '' }}">LANTAI {{ $rowData['level'] }}{{ $rowData['subLevel'] ? ' ' . $rowData['subLevel'] : '' }}</span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($rowData['col3']['location']->location_code) !!}
                    </div>
                    <span class="location-code">{{ $rowData['col3']['location']->location_code }}</span>
                    @endif
                </div>
                
                {{-- Column 4 --}}
                <div class="qr-cell {{ !$rowData['col4'] ? 'empty' : '' }}">
                    @if($rowData['col4'])
                    <span class="floor-label" data-floor="{{ $rowData['level'] }}" data-sublevel="{{ $rowData['subLevel'] ?? '' }}">LANTAI {{ $rowData['level'] }}{{ $rowData['subLevel'] ? ' ' . $rowData['subLevel'] : '' }}</span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($rowData['col4']['location']->location_code) !!}
                    </div>
                    <span class="location-code">{{ $rowData['col4']['location']->location_code }}</span>
                    @endif
                </div>
            @endforeach


        </div>
    </div>
    @endforeach
    </div>
    
    <script>
        // SVG templates for arrows
        const leftArrowSVG = `<svg class="arrow-left" viewBox="0 0 100 30"><polygon points="0,15 20,0 20,10 100,10 100,20 20,20 20,30" fill="black"/></svg>`;
        const rightArrowSVG = `<svg class="arrow-right" viewBox="0 0 100 30"><polygon points="100,15 80,0 80,10 0,10 0,20 80,20 80,30" fill="black"/></svg>`;
        
        // Dynamic barcode count per page
        let currentRowsPerPage = 5;
        let originalPagesHTML = null;
        
        // Save original structure on load
        document.addEventListener('DOMContentLoaded', function() {
            originalPagesHTML = document.getElementById('pages-container').innerHTML;
        });
        
        function updateBarcodeCount(count) {
            count = parseInt(count);
            const rowsPerPage = count / 4;
            currentRowsPerPage = rowsPerPage;
            const badge = document.getElementById('orientation-badge');
            const styleEl = document.getElementById('page-orientation');
            
            if (count >= 16) {
                // Portrait - 5 or 4 rows
                document.documentElement.style.setProperty('--page-width', '210mm');
                document.documentElement.style.setProperty('--page-height', '291mm');
                styleEl.textContent = '@media print { @page { size: A4 portrait; margin: 3mm; } }';
                badge.innerHTML = '\ud83d\udcd0 A4 Portrait: 4 kolom \u00d7 ' + rowsPerPage + ' baris = ' + count + ' lokasi/halaman';
            } else {
                // Landscape - 3 or 2 rows
                document.documentElement.style.setProperty('--page-width', '291mm');
                document.documentElement.style.setProperty('--page-height', '204mm');
                styleEl.textContent = '@media print { @page { size: A4 landscape; margin: 3mm; } }';
                badge.innerHTML = '\ud83d\udcd0 A4 Landscape: 4 kolom \u00d7 ' + rowsPerPage + ' baris = ' + count + ' lokasi/halaman';
            }
            
            restructurePages(rowsPerPage);
        }
        
        function restructurePages(rowsPerPage) {
            const cols = 4;
            const itemsPerPage = rowsPerPage * cols;
            const container = document.getElementById('pages-container');
            
            // Restore original HTML first to get all items back
            container.innerHTML = originalPagesHTML;
            
            const originalPages = container.querySelectorAll('.page');
            
            // Helper: get level from a cell's floor-label
            function getCellLevel(cell) {
                const label = cell.querySelector('.floor-label');
                if (!label) return null;
                return parseInt(label.getAttribute('data-floor')) || null;
            }
            
            // Helper: get first/last non-null level from cells array
            function getFirstLevel(cells) {
                for (const c of cells) { const l = getCellLevel(c); if (l !== null) return l; }
                return null;
            }
            function getLastLevel(cells) {
                for (let i = cells.length - 1; i >= 0; i--) { const l = getCellLevel(cells[i]); if (l !== null) return l; }
                return null;
            }
            
            // Step 1: Group consecutive pages that belong to same column set
            // Same set = next page's first level is LOWER than current group's last level (descending)
            const columnGroups = [];
            let currentGroup = null;
            
            originalPages.forEach(page => {
                const grid = page.querySelector('.rack-grid');
                const arrows = Array.from(grid.querySelectorAll('.arrow-cell')).map(ac => ac.cloneNode(true));
                const cells = Array.from(grid.querySelectorAll('.qr-cell')).map(c => c.cloneNode(true));
                
                if (!currentGroup) {
                    currentGroup = { arrows: arrows, cells: [...cells] };
                } else {
                    const lastLvl = getLastLevel(currentGroup.cells);
                    const firstLvl = getFirstLevel(cells);
                    
                    if (lastLvl !== null && firstLvl !== null && firstLvl <= lastLvl) {
                        // Same column set (levels still descending or equal), merge cells
                        currentGroup.cells.push(...cells);
                    } else {
                        // New column set — flush current group, start new
                        columnGroups.push(currentGroup);
                        currentGroup = { arrows: arrows, cells: [...cells] };
                    }
                }
            });
            if (currentGroup) columnGroups.push(currentGroup);
            
            // Step 2: Re-chunk each column group into landscape-sized pages
            const allChunks = [];
            columnGroups.forEach(group => {
                for (let i = 0; i < group.cells.length; i += itemsPerPage) {
                    allChunks.push({
                        arrows: group.arrows,
                        cells: group.cells.slice(i, i + itemsPerPage)
                    });
                }
            });
            
            // Step 3: Build pages
            container.innerHTML = '';
            
            allChunks.forEach((chunk, idx) => {
                const pageDiv = document.createElement('div');
                pageDiv.className = 'page' + (idx < allChunks.length - 1 ? ' page-break' : '');
                
                const gridDiv = document.createElement('div');
                gridDiv.className = 'rack-grid';
                gridDiv.style.gridTemplateRows = 'var(--arrow-row-height, 14mm) repeat(' + rowsPerPage + ', 1fr)';
                
                chunk.arrows.forEach(ac => gridDiv.appendChild(ac.cloneNode(true)));
                chunk.cells.forEach(cell => gridDiv.appendChild(cell));
                
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
