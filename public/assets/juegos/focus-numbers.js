(function(){
    // ---- DOM
    const dobEl = document.getElementById('dob');
    const ageOut = document.getElementById('ageOut');
    const gridSizeEl = document.getElementById('gridSize');
    const rangeOut = document.getElementById('rangeOut');

    const btnSet = document.getElementById('btnSet');
    const btnStart = document.getElementById('btnStart');
    const btnRetry = document.getElementById('btnRetry');
    const btnReveal = document.getElementById('btnReveal');
    const btnShuffleStart = document.getElementById('btnShuffleStart');

    const gridWrap = document.getElementById('grid');
    const hudNext = document.getElementById('hudNext');
    const hudProgress = document.getElementById('hudProgress');
    const hudTime = document.getElementById('hudTime');
    const hudClicks = document.getElementById('hudClicks');
    const messages = document.getElementById('messages');
    const logTimesEl = document.getElementById('logTimes');
    const btnExportCsv = document.getElementById('btnExportCsv');

    // Resultado
    const resultCard = document.getElementById('resultCard');
    const resNumbers = document.getElementById('resNumbers');
    const resPercent = document.getElementById('resPercent');
    const resLevel = document.getElementById('resLevel');

    // ---- Estado
    let N = 0, totalCells = 0;
    let numberMin = 0, numberMaxWanted = 49, numberMaxPlaced = 0;
    let mapping = [];
    let numbers = [];
    let nextIndex = 0;
    let clicksValid = 0;

    let timeStart = 0;
    let timerId = null;
    let running = false;

    let prevClickTime = null; // Performance.now() ms
    let clickLog = [];        // [{num:'00', t_ms:..., delta_ms:...}]

    // ---- Utilidades
    function pad2(n){ return n.toString().padStart(2,'0'); }
    function msToSec(ms){ return (ms/1000).toFixed(3); }
    function calcAgeFromDOB(v){
        if(!v) return null;
        const dob = new Date(v + 'T00:00:00');
        if(isNaN(dob.getTime())) return null;
        const now = new Date();
        let age = now.getFullYear() - dob.getFullYear();
        const m = now.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && now.getDate() < dob.getDate())) age--;
        return Math.max(0, age);
    }
    function mapAgeToGridSize(age){
        if(age >= 5 && age <= 7) return 7;
        if(age >= 8 && age <= 9) return 8;
        if(age >= 10 && age <= 11) return 9;
        if(age >= 12 && age <= 13) return 10;
        if(age >= 14 && age <= 15) return 11;
        if(age >= 16 && age <= 17) return 12;
        return 13;
    }
    function shuffle(arr){
        for(let i=arr.length-1;i>0;i--){
            const j = Math.floor(Math.random()*(i+1));
            [arr[i],arr[j]]=[arr[j],arr[i]];
        }
        return arr;
    }
    function setStatus(msg, type='info'){
        const palette = { info:'text-slate-600', good:'text-green-700', bad:'text-rose-700' };
        messages.className = `mt-4 text-center text-sm ${palette[type]||palette.info}`;
        messages.textContent = msg || '';
    }

    // ---- Tabla de evaluación (según tu especificación)
    function evaluateLevel(marked){
        // cap superior 30, para encajar con la tabla
        const n = Math.max(0, Math.min(marked, 30));
        if(n <= 4)   return { percent: '0–16%',  level: 'Débil' };
        if(n <= 9)   return { percent: '17–32%', level: 'Necesita mejorar' };
        if(n <= 15)  return { percent: '33–52%', level: 'Promedio' };
        if(n <= 21)  return { percent: '53–72%', level: 'Bueno' };
        // 22–30
        return { percent: '73–100%', level: 'Excelente' };
    }

    function renderResult(marked){
        const ev = evaluateLevel(marked);
        resNumbers.textContent = String(marked);
        resPercent.textContent = ev.percent;
        resLevel.textContent = ev.level;
        resultCard.classList.remove('hidden');
    }
    function hideResult(){
        resultCard.classList.add('hidden');
        resNumbers.textContent = '0';
        resPercent.textContent = '—';
        resLevel.textContent = '—';
    }

    // ---- Grilla
    function drawGrid(){
        gridWrap.innerHTML = '';
        gridWrap.style.gridTemplateColumns = `repeat(${N}, var(--cell-size))`;
        gridWrap.style.gridTemplateRows = `repeat(${N}, var(--cell-size))`;
        for(let i=0;i<totalCells;i++){
            const btn = document.createElement('button');
            btn.type='button';
            btn.className='cell disabled';
            btn.dataset.pos = i;
            btn.addEventListener('click', onCellClick);
            const span = document.createElement('span');
            span.className='hidden-number';
            span.textContent='';
            btn.appendChild(span);
            gridWrap.appendChild(btn);
        }
    }
    function cellAtPos(pos){ return gridWrap.querySelector(`.cell[data-pos="${pos}"]`); }

    // ---- Preparación del tablero
    function prepareBoard(){
        mapping = Array.from({length: totalCells}, (_,i)=>i);
        shuffle(mapping);

        const maxByCells = totalCells - 1; // 0..(totalCells-1)
        numberMaxPlaced = Math.min(numberMaxWanted, maxByCells);
        numbers = [];
        for(let n=numberMin; n<=numberMaxPlaced; n++) numbers.push(pad2(n));

        for(let i=0;i<numbers.length;i++){
            const pos = mapping[i];
            const cell = cellAtPos(pos);
            const span = cell.querySelector('span');
            span.textContent = numbers[i];
        }

        // Ocultar y deshabilitar
        Array.from(gridWrap.children).forEach(c=>{
            const span = c.querySelector('span');
            if(span.textContent){
                span.classList.remove('revealed');
                span.classList.add('hidden-number');
            }
            c.classList.add('disabled');
            c.classList.remove('correct','wrong');
        });

        // HUD & tiempos
        nextIndex = 0;
        clicksValid = 0;
        hudNext.textContent = numbers.length ? numbers[0] : '—';
        hudProgress.textContent = `0 / ${numbers.length}`;
        hudClicks.textContent = '0';
        hudTime.textContent = '60.0s';

        stopTimer();
        timeStart = 0;
        prevClickTime = null;
        clickLog = [];
        renderLog();
        hideResult();

        running = false;

        btnReveal.classList.remove('hidden');
        btnRetry.classList.add('hidden');
    }

    // ---- Juego
    function startGame(){
        if(!numbers.length) return;
        running = true;
        timeStart = performance.now();

        Array.from(gridWrap.children).forEach(c=>{
            const span = c.querySelector('span');
            if(span.textContent){
                span.classList.remove('hidden-number');
                span.classList.add('revealed');
                c.classList.remove('disabled');
            } else {
                c.classList.add('disabled');
            }
        });
        setStatus('¡Empieza! Marca los números en orden ascendente (00, 01, 02, ...).');

        stopTimer();
        timerId = setInterval(()=>{
            const elapsed = performance.now() - timeStart;
            const remain = Math.max(0, 60000 - elapsed);
            hudTime.textContent = (remain/1000).toFixed(1)+'s';
            if(remain <= 0) endGame(false);
        }, 100);
    }
    function stopTimer(){ if(timerId){ clearInterval(timerId); timerId = null; } }

    function endGame(byCompletion){
        running = false;
        stopTimer();
        Array.from(gridWrap.children).forEach(c=> c.classList.add('disabled'));
        btnRetry.classList.remove('hidden');
        btnReveal.classList.add('hidden');

        // Render del resultado según la tabla
        renderResult(clicksValid);

        setStatus(byCompletion ? '¡Completado! Excelente concentración 👏' : 'Se acabó el tiempo. ¡Buen intento! Vuelve a intentarlo.', byCompletion ? 'good' : 'bad');
    }

    // ---- Click
    function onCellClick(e){
        if(!running) return;
        const cell = e.currentTarget;
        if(cell.classList.contains('disabled')) return;

        const span = cell.querySelector('span');
        const value = span.textContent || '';
        const expected = numbers[nextIndex];

        if(value === expected){
            cell.classList.add('correct','disabled');
            cell.classList.remove('wrong');

            clicksValid++;
            hudClicks.textContent = String(clicksValid);
            nextIndex++;
            hudProgress.textContent = `${nextIndex} / ${numbers.length}`;
            hudNext.textContent = nextIndex < numbers.length ? numbers[nextIndex] : '✔';

            const now = performance.now();
            const delta = (prevClickTime === null) ? (now - timeStart) : (now - prevClickTime);
            prevClickTime = now;
            clickLog.push({ num:value, t_ms: now - timeStart, delta_ms: delta });
            renderLog();

            if(nextIndex >= numbers.length) endGame(true);
        } else {
            cell.classList.add('wrong');
            setTimeout(()=> cell.classList.remove('wrong'), 250);
        }
    }

    // ---- Log (en segundos)
    function renderLog(){
        if(!clickLog.length){
            logTimesEl.textContent = '—';
            return;
        }
        const lines = clickLog.map(x => {
            const tSec = msToSec(x.t_ms);
            const dSec = msToSec(x.delta_ms);
            return `${x.num}\t t=${tSec}s\t Δ=${dSec}s`;
        });
        const timeline = 'Timeline (s): ' + clickLog.map(x => msToSec(x.t_ms)).join(', ');
        logTimesEl.textContent = lines.join('\n') + '\n\n' + timeline;
    }

    // ---- CSV export
    function exportCSV(){
        // Encabezado y filas de tiempos
        const rows = [['numero','t_seg','delta_seg']];
        for(const x of clickLog){
            rows.push([x.num, msToSec(x.t_ms), msToSec(x.delta_ms)]);
        }
        // Resumen
        const ev = evaluateLevel(clicksValid);
        rows.push([]);
        rows.push(['resumen']);
        rows.push(['numeros_marcados', 'porcentaje_tabla', 'nivel']);
        rows.push([String(clicksValid), ev.percent, ev.level]);

        // Serializar CSV
        const csv = rows.map(r => r.map(v => `"${(v??'').toString().replace(/"/g,'""')}"`).join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);

        // Descargar
        const a = document.createElement('a');
        a.href = url;
        const now = new Date();
        const ts = now.toISOString().replace(/[:.]/g,'-');
        a.download = `focus_numbers_${N}x${N}_${ts}.csv`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // ---- Setup handlers
    btnSet.addEventListener('click', ()=>{
        const age = calcAgeFromDOB(dobEl.value);
        if(age === null){
            ageOut.textContent = 'Edad: —';
            gridSizeEl.value = '';
            setStatus('Ingresa una fecha de nacimiento válida.', 'bad');
            btnStart.disabled = true;
            return;
        }
        ageOut.textContent = `Edad: ${age} años`;
        N = mapAgeToGridSize(age);
        totalCells = N * N;
        gridSizeEl.value = `${N} × ${N}`;

        drawGrid();
        prepareBoard();

        const maxLabel = pad2(numberMaxPlaced);
        const targetMax = pad2(numberMaxWanted);
        rangeOut.value = (numberMaxPlaced === numberMaxWanted) ? `00–${targetMax}` : `00–${maxLabel} (ajustado)`;

        setStatus('Configuración aplicada. Presiona “Iniciar” o “Reordenar y comenzar”.');
        btnStart.disabled = false;
    });

    btnStart.addEventListener('click', ()=>{
        if(!N || !numbers.length) return;
        startGame();
    });

    btnRetry.addEventListener('click', ()=>{
        if(!N) return;
        stopTimer();
        drawGrid();
        prepareBoard();
        setStatus('Listo. Presiona “Iniciar” o “Reordenar y comenzar”.');
        btnStart.disabled = false;
    });

    btnShuffleStart.addEventListener('click', ()=>{
        if(!N) return;
        stopTimer();
        drawGrid();
        prepareBoard();
        startGame();
    });

    btnReveal.addEventListener('click', ()=>{
        Array.from(gridWrap.children).forEach(c=>{
            const span = c.querySelector('span');
            if(span.textContent){
                span.classList.remove('hidden-number'); span.classList.add('revealed');
            }
        });
    });

    btnExportCsv.addEventListener('click', exportCSV);

    // Inicial
    setStatus('Configura la edad con la fecha de nacimiento y luego “Establecer”.');
})();
