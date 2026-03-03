<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Lokasi Ground Shelving</title>
    <style id="page-orientation">
        @media print {
            @page {
                size: A4 portrait;
                margin: 3mm;
            }
        }
    </style>
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

        /* Grid Container */
        .shelving-grid {
            display: grid;
            grid-template-columns: repeat(var(--grid-cols, 2), 1fr);
            grid-template-rows: repeat(var(--grid-rows, 7), 1fr);
            row-gap: var(--row-gap, 0px);
            column-gap: var(--col-gap, 0px);
            border: var(--border-width, 0.5px) solid black;
            width: 100%;
            height: 100%;
        }

        /* Label Cell - horizontal strip */
        .label-cell {
            border: var(--border-width, 0.5px) solid black;
            padding: 0 var(--cell-padding, 2mm);
            display: flex;
            flex-direction: row;
            align-items: stretch;
            justify-content: center;
            gap: 0;
            min-height: 0;
            overflow: hidden;
        }

        .label-cell.empty {
            background: white;
            border-color: transparent;
        }

        /* Large text on the left */
        .location-text {
            flex: 1;
            font-size: var(--font-size, 28px);
            font-weight: var(--font-weight, 900);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            letter-spacing: 1px;
            border-right: var(--border-width, 0.5px) solid black;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 var(--label-gap, 3mm);
        }

        /* QR code on the right */
        .qr-code {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--cell-padding, 2mm);
        }

        .qr-code svg {
            width: var(--qr-size, 25mm);
            height: var(--qr-size, 25mm);
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
        
        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 10px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- Floating Control Panel --}}
    <div class="control-panel no-print">
        <h3>⚙️ Pengaturan Cetak Ground Shelving</h3>
        
        <div id="orientation-badge" class="badge">📐 A4 Portrait: 2 kolom × 7 baris = 14 lokasi/halaman</div>
        
        {{-- Section: Label Count --}}
        <div class="control-section">
            <h4>📊 Jumlah Label / Halaman</h4>
            <div class="slider-group">
                <label>Layout:</label>
                <select id="layout-select" onchange="updateLayout(this.value)" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;font-size:12px;cursor:pointer">
                    <option value="7x2" selected>7 baris × 2 kolom = 14 (Portrait)</option>
                    <option value="5x2">5 baris × 2 kolom = 10 (Portrait)</option>
                    <option value="4x3">4 baris × 3 kolom = 12 (Landscape)</option>
                    <option value="5x3">5 baris × 3 kolom = 15 (Landscape)</option>
                </select>
            </div>
        </div>
        
        {{-- Section: Ukuran --}}
        <div class="control-section">
            <h4>📐 Ukuran</h4>
            <div class="slider-group">
                <label>QR Code: <span id="qr-size-value" class="slider-value">25mm</span></label>
                <input type="range" id="qr-size-slider" min="10" max="40" value="25" oninput="updateQRSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Font Lokasi: <span id="font-size-value" class="slider-value">28px</span></label>
                <input type="range" id="font-size-slider" min="14" max="60" value="28" oninput="updateFontSize(this.value)">
            </div>
            <div class="slider-group">
                <label>Font Weight: <span id="font-weight-value" class="slider-value">900</span></label>
                <input type="range" id="font-weight-slider" min="400" max="900" step="100" value="900" oninput="updateFontWeight(this.value)">
            </div>
            <div class="slider-group">
                <label>Cell Padding: <span id="cell-padding-value" class="slider-value">2mm</span></label>
                <input type="range" id="cell-padding-slider" min="0" max="10" value="2" oninput="updateCellPadding(this.value)">
            </div>
            <div class="slider-group">
                <label>Row Gap: <span id="row-gap-value" class="slider-value">0mm</span></label>
                <input type="range" id="row-gap-slider" min="0" max="10" value="0" oninput="updateRowGap(this.value)">
            </div>
        </div>
        
        {{-- Section: Border --}}
        <div class="control-section">
            <h4>🔲 Border</h4>
            <div class="slider-group">
                <label>Ketebalan: <span id="border-width-value" class="slider-value">0.5px</span></label>
                <input type="range" id="border-width-slider" min="0" max="30" value="5" oninput="updateBorderWidth(this.value)">
            </div>
            <button class="control-btn" id="toggle-border-btn" onclick="toggleBorder()">
                🚫 Hapus Border
            </button>
        </div>
        
        <button class="control-btn primary" onclick="window.print()">
            🖨️ Cetak
        </button>
        
        <div class="hint">
            💡 Klik Cetak untuk mencetak. Semua pengaturan hanya berlaku di preview.
        </div>
    </div>

    @php
        // Simple flat list — just chunk into pages
        $itemsPerPage = 14; // 7 rows × 2 cols
        $locationList = $locations->values();
        $pages = $locationList->chunk($itemsPerPage);
    @endphp

    <div id="pages-container">
    @foreach($pages as $pageIndex => $pageLocations)
    
    <div class="page {{ !$loop->last ? 'page-break' : '' }}">
        <div class="shelving-grid">
            @foreach($pageLocations as $loc)
                <div class="label-cell" data-location="{{ $loc->location_code }}">
                    <span class="location-text">{{ $loc->location_code }}</span>
                    <div class="qr-code">
                        {!! QrCode::size(80)->generate($loc->location_code) !!}
                    </div>
                </div>
            @endforeach
            
            {{-- Pad remaining cells to fill the grid --}}
            @for($i = $pageLocations->count(); $i < $itemsPerPage; $i++)
                <div class="label-cell empty"></div>
            @endfor
        </div>
    </div>
    @endforeach
    </div>
    
    <script>
        // Current layout state
        let currentRows = 7;
        let currentCols = 2;
        let currentItemsPerPage = 14;
        let originalPagesHTML = null;
        
        // Save original structure on load
        document.addEventListener('DOMContentLoaded', function() {
            originalPagesHTML = document.getElementById('pages-container').innerHTML;
        });
        
        // Update layout (rows × cols)
        function updateLayout(value) {
            const layouts = {
                '7x2': { rows: 7, cols: 2, portrait: true },
                '5x2': { rows: 5, cols: 2, portrait: true },
                '4x3': { rows: 4, cols: 3, portrait: false },
                '5x3': { rows: 5, cols: 3, portrait: false },
            };
            
            const layout = layouts[value];
            if (!layout) return;
            
            currentRows = layout.rows;
            currentCols = layout.cols;
            currentItemsPerPage = layout.rows * layout.cols;
            
            // Update CSS grid
            document.documentElement.style.setProperty('--grid-rows', layout.rows);
            document.documentElement.style.setProperty('--grid-cols', layout.cols);
            
            // Update page orientation
            const badge = document.getElementById('orientation-badge');
            const styleEl = document.getElementById('page-orientation');
            
            if (layout.portrait) {
                document.documentElement.style.setProperty('--page-width', '210mm');
                document.documentElement.style.setProperty('--page-height', '291mm');
                styleEl.textContent = '@media print { @page { size: A4 portrait; margin: 3mm; } }';
                badge.innerHTML = '📐 A4 Portrait: ' + currentCols + ' kolom × ' + currentRows + ' baris = ' + currentItemsPerPage + ' lokasi/halaman';
            } else {
                document.documentElement.style.setProperty('--page-width', '291mm');
                document.documentElement.style.setProperty('--page-height', '204mm');
                styleEl.textContent = '@media print { @page { size: A4 landscape; margin: 3mm; } }';
                badge.innerHTML = '📐 A4 Landscape: ' + currentCols + ' kolom × ' + currentRows + ' baris = ' + currentItemsPerPage + ' lokasi/halaman';
            }
            
            // Restructure pages
            restructurePages();
        }
        
        // Restructure pages based on current layout
        function restructurePages() {
            const container = document.getElementById('pages-container');
            
            // Reset to original HTML first
            if (originalPagesHTML) {
                container.innerHTML = originalPagesHTML;
            }
            
            // Collect all label cells (non-empty)
            const allCells = [];
            container.querySelectorAll('.label-cell:not(.empty)').forEach(cell => {
                allCells.push(cell.cloneNode(true));
            });
            
            // Clear container
            container.innerHTML = '';
            
            // Re-chunk into pages
            const totalPages = Math.ceil(allCells.length / currentItemsPerPage);
            
            for (let p = 0; p < totalPages; p++) {
                const pageDiv = document.createElement('div');
                pageDiv.className = 'page' + (p < totalPages - 1 ? ' page-break' : '');
                
                const gridDiv = document.createElement('div');
                gridDiv.className = 'shelving-grid';
                
                // Add cells for this page
                const start = p * currentItemsPerPage;
                const end = Math.min(start + currentItemsPerPage, allCells.length);
                
                for (let i = start; i < end; i++) {
                    gridDiv.appendChild(allCells[i]);
                }
                
                // Pad with empty cells
                const remaining = currentItemsPerPage - (end - start);
                for (let i = 0; i < remaining; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'label-cell empty';
                    gridDiv.appendChild(emptyCell);
                }
                
                pageDiv.appendChild(gridDiv);
                container.appendChild(pageDiv);
            }
        }
        
        // Update QR Code size
        function updateQRSize(value) {
            document.documentElement.style.setProperty('--qr-size', value + 'mm');
            document.getElementById('qr-size-value').textContent = value + 'mm';
        }
        
        // Update Font size
        function updateFontSize(value) {
            document.documentElement.style.setProperty('--font-size', value + 'px');
            document.getElementById('font-size-value').textContent = value + 'px';
        }
        
        // Update Font weight
        function updateFontWeight(value) {
            document.documentElement.style.setProperty('--font-weight', value);
            document.getElementById('font-weight-value').textContent = value;
        }
        
        // Update Cell Padding
        function updateCellPadding(value) {
            document.documentElement.style.setProperty('--cell-padding', value + 'mm');
            document.getElementById('cell-padding-value').textContent = value + 'mm';
        }
        
        // Update Row Gap
        function updateRowGap(value) {
            document.documentElement.style.setProperty('--row-gap', value + 'mm');
            document.getElementById('row-gap-value').textContent = value + 'mm';
        }
        
        // Update Border Width
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
    </script>

</body>

</html>
