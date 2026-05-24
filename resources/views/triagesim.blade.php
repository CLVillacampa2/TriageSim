<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>TriageSim - Interactive First Aid and Triage Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            bg: '#eef9f5', mint: '#d1f2e9', mintLight: '#e0f4f0',
                            teal: '#0096a5', tealDark: '#087a86', tealDeep: '#065f68',
                            accent: '#00a887', dark: '#1e293b', muted: '#64748b'
                        },
                        triage: {
                            red: '#ef4444', yellow: '#eab308', green: '#22c55e', black: '#1e293b'
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { background-color: #eef9f5; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .premium-card {
            background: #ffffff; border: 2px solid #d1f2e9; border-radius: 16px;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.03); transition: all 0.3s ease;
        }
        .premium-card-hover:hover {
            transform: translateY(-4px); box-shadow: 0 12px 20px -8px rgba(0, 150, 165, 0.15); border-color: #0096a5;
        }
        .view-frame { display: none; animation: slideUpFade 0.4s ease forwards; }
        .view-frame.active { display: block; }
        @keyframes slideUpFade { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #eef9f5; }
        ::-webkit-scrollbar-thumb { background: #c3eade; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #0096a5; }
    </style>
</head>
<body class="min-height-screen flex flex-col items-center justify-center p-4 md:p-8">

    <header class="w-full max-w-6xl mb-8 flex items-center justify-between">
        <div class="flex items-center gap-3 cursor-pointer" onclick="showView('homeView')">
            <div class="w-10 h-10 bg-brand-accent rounded-full flex items-center justify-center text-white shadow-md">
                <i data-lucide="check" class="w-5 h-5 stroke-[3]"></i>
            </div>
            <div>
                <span class="text-2xl font-bold tracking-tight text-brand-teal">TriageSim</span>
                <span class="hidden md:inline-block ml-2 px-2.5 py-0.5 text-xs font-semibold bg-brand-mintLight text-brand-tealDark rounded-full">v2.0 Stable</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="toggleHelpModal()" class="w-10 h-10 rounded-full border-2 border-brand-mint bg-white hover:bg-brand-mintLight text-brand-tealDark flex items-center justify-center transition-colors">
                <i data-lucide="help-circle" class="w-5 h-5"></i>
            </button>
        </div>
    </header>

    <div id="helpModal" class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border-2 border-brand-mint max-w-lg w-full overflow-hidden shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white flex justify-between items-center">
                <h3 class="text-lg font-bold flex items-center gap-2"><i data-lucide="book-open" class="w-5 h-5"></i> START Triage Reference</h3>
                <button onclick="toggleHelpModal()" class="text-white/80 hover:text-white"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>
            <div class="p-6 space-y-4 text-sm text-brand-dark overflow-y-auto max-h-[70vh]">
                <div class="p-3 bg-red-50 border-l-4 border-triage-red rounded-r-lg"><strong class="text-triage-red block">Immediate (Red)</strong> Resp > 30, CRT > 2s, or can't follow commands.</div>
                <div class="p-3 bg-yellow-50 border-l-4 border-triage-yellow rounded-r-lg"><strong class="text-yellow-700 block">Delayed (Yellow)</strong> Resp < 30, CRT < 2s, but cannot walk.</div>
                <div class="p-3 bg-green-50 border-l-4 border-triage-green rounded-r-lg"><strong class="text-triage-green block">Minor (Green)</strong> All "walking wounded".</div>
            </div>
        </div>
    </div>

    <div id="exitConfirmModal" class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border-2 border-brand-mint max-w-sm w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <h3 class="text-lg font-bold text-brand-dark mb-2 flex items-center gap-2 text-red-500"><i data-lucide="alert-triangle" class="w-5 h-5"></i> Exit Simulation?</h3>
            <p class="text-sm text-brand-muted mb-6">Are you sure you want to quit? Current progress will be lost.</p>
            <div class="flex gap-3">
                <button onclick="closeExitModal()" class="flex-1 py-2.5 px-4 border-2 border-brand-mint text-brand-tealDark font-bold rounded-xl hover:bg-brand-bg transition-colors text-sm">Cancel</button>
                <button onclick="executeExitSimulation()" class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors text-sm">Exit Scenario</button>
            </div>
        </div>
    </div>

    <div id="susModal" class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border-2 border-brand-mint max-w-md w-full p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-brand-dark mb-2 flex items-center gap-2 text-brand-teal"><i data-lucide="clipboard-check" class="w-5 h-5"></i> System Usability Scale (SUS)</h3>
            <p class="text-xs text-brand-muted mb-4 pb-4 border-b border-brand-bg">Rate the platform's student-friendliness (1 = Disagree, 5 = Agree).</p>
            <div class="space-y-4 mb-6">
                <div>
                    <p class="text-sm font-semibold text-brand-dark mb-2">1. I think that I would like to use this system frequently.</p>
                    <div class="flex gap-2">
                        <button class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white text-xs">1</button>
                        <button class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white text-xs">2</button>
                        <button class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white text-xs">3</button>
                        <button class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white text-xs">4</button>
                        <button class="flex-1 py-2 border border-brand-mint bg-brand-teal text-white text-xs">5</button>
                    </div>
                </div>
            </div>
            <button onclick="closeSUSModal()" class="w-full py-3 px-4 bg-brand-accent hover:bg-brand-tealDark text-white font-bold rounded-xl transition-colors text-sm">Submit Survey</button>
        </div>
    </div>

    <main id="homeView" class="view-frame active w-full max-w-5xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-brand-dark mb-4">Branching-Scenario <span class="text-brand-teal">Emergency Triage</span></h1>
            <p class="text-lg text-brand-muted max-w-2xl mx-auto font-medium">Interactive Finite State Machine models and precise decision latency telemetry.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <div class="premium-card premium-card-hover p-8 cursor-pointer flex flex-col justify-between" onclick="showView('studentSetupView')">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-brand-mintLight flex items-center justify-center text-brand-teal mb-6"><i data-lucide="play-circle" class="w-8 h-8"></i></div>
                    <h2 class="text-2xl font-bold text-brand-dark mb-3">Student Simulator</h2>
                    <p class="text-brand-muted mb-6">Enter a safe-to-fail scenario. Path efficiency and latency are tracked.</p>
                </div>
            </div>
            <div class="premium-card premium-card-hover p-8 cursor-pointer flex flex-col justify-between" onclick="showView('loginView')">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-brand-mintLight flex items-center justify-center text-brand-teal mb-6"><i data-lucide="line-chart" class="w-8 h-8"></i></div>
                    <h2 class="text-2xl font-bold text-brand-dark mb-3">Instructor Dashboard</h2>
                    <p class="text-brand-muted mb-6">Access analytics, cognitive heatmaps, and individual optimization metrics.</p>
                </div>
            </div>
        </div>
    </main>

    <main id="studentSetupView" class="view-frame w-full max-w-md">
        <button onclick="showView('homeView')" class="mb-6 flex items-center gap-2 text-brand-tealDark hover:text-brand-teal font-semibold text-sm"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</button>
        <div class="premium-card overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center"><i data-lucide="user-cog" class="w-6 h-6"></i></div>
                <div><h2 class="text-xl font-bold">Profile Setup</h2><p class="text-xs text-brand-mintLight">Provide details for logging</p></div>
            </div>
            <div class="p-6 space-y-5 bg-white">
                <div><label class="block text-xs font-bold text-brand-dark mb-2">Student Name</label><input type="text" id="studentInputName" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl" placeholder="e.g. John Doe"></div>
                <div><label class="block text-xs font-bold text-brand-dark mb-2">Student ID</label><input type="text" id="studentInputId" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl" placeholder="e.g. STU2026118"></div>
                <div>
                    <label class="block text-xs font-bold text-brand-dark mb-2">Cohort / Section</label>
                    <select id="studentInputCohort" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl">
                        <option value="NURS-301-A">NURS-301-A (Mon/Wed)</option>
                        <option value="NURS-301-B">NURS-301-B (Tue/Thu)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-brand-dark mb-2">Scenario</label>
                    <select id="studentInputScenario" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl">
                        <option value="START_NODE">Scenario A: Mass Casualty</option>
                        <option value="SCENARIO_B_START">Scenario B: Structural Fire</option>
                    </select>
                </div>
                <button onclick="startStudentMode()" class="w-full bg-brand-accent hover:bg-brand-tealDark text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2"><i data-lucide="play" class="w-4 h-4"></i> Launch Simulator</button>
            </div>
        </div>
    </main>

    <main id="loginView" class="view-frame w-full max-w-md">
        <button onclick="showView('homeView')" class="mb-6 flex items-center gap-2 text-brand-tealDark hover:text-brand-teal font-semibold text-sm"><i data-lucide="arrow-left" class="w-4 h-4"></i> Back</button>
        <div class="premium-card overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white"><h2 class="text-xl font-bold">Instructor Login</h2></div>
            <div class="p-6 space-y-5 bg-white">
                <div><label class="block text-xs font-bold mb-2">Email</label><input type="email" id="loginEmail" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl" value="instructor@university.edu"></div>
                <div><label class="block text-xs font-bold mb-2">Password</label><input type="password" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl" value="password"></div>
                <button onclick="executeLogin()" class="w-full bg-brand-teal text-white font-bold py-3.5 px-4 rounded-xl">Authenticate</button>
            </div>
        </div>
    </main>

    <main id="studentView" class="view-frame w-full max-w-5xl">
        <div class="flex justify-between gap-4 mb-6">
            <div><h1 class="text-2xl font-extrabold text-brand-dark">Clinical Simulation Terminal</h1></div>
            <button onclick="confirmExitSimulation()" class="px-4 py-2 border-2 border-brand-mint bg-white text-brand-tealDark font-bold rounded-xl"><i data-lucide="log-out" class="w-4 h-4 inline"></i> Exit</button>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="premium-card p-6 bg-white space-y-6">
                    <div class="border-b border-brand-bg pb-4 font-bold text-brand-dark" id="scenarioTitle">Active Case</div>
                    <div id="nodePrompt" class="p-5 bg-brand-bg rounded-xl font-medium border-l-4 border-brand-teal">Loading...</div>
                    <div id="optionsWrapper" class="flex flex-col gap-3"></div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="premium-card p-6 bg-white space-y-5">
                    <h3 class="font-bold text-brand-dark border-b border-brand-bg pb-3">Telemetry</h3>
                    <div class="flex justify-between text-sm"><span class="text-brand-muted">Name:</span> <span id="telemetryName" class="font-bold">Participant</span></div>
                    <div class="flex justify-between text-sm"><span class="text-brand-muted">Node:</span> <span id="telemetryState" class="font-bold">START</span></div>
                    <div class="flex justify-between text-sm"><span class="text-brand-muted">Latency (L):</span> <span id="telemetryLatency" class="font-bold text-red-500">0.00s</span></div>
                    <div class="flex justify-between text-sm"><span class="text-brand-muted">Efficiency (E):</span> <span id="telemetryEfficiency" class="font-bold text-brand-accent">100%</span></div>
                </div>
            </div>
        </div>
    </main>

    <main id="dashboardView" class="view-frame w-full max-w-6xl">
        <div class="premium-card p-5 bg-white mb-6 flex justify-between items-center">
            <h2 class="font-bold text-brand-dark">Performance Analytics Panel</h2>
            <div class="flex gap-4">
                <button onclick="exportToCSV()" class="px-4 py-2 bg-brand-accent text-white font-bold rounded-xl text-sm"><i data-lucide="download" class="w-4 h-4 inline"></i> Export CSV</button>
                <button onclick="showView('homeView')" class="px-4 py-2 bg-brand-bg text-brand-tealDark font-bold rounded-xl text-sm">Logout</button>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="premium-card p-5">Total Students: <div id="dash-total-students" class="text-3xl font-black mt-2">0</div></div>
            <div class="premium-card p-5">Avg Latency: <div id="dash-avg-latency" class="text-3xl font-black mt-2 text-red-500">0s</div></div>
            <div class="premium-card p-5">Mean Efficiency: <div id="dash-avg-efficiency" class="text-3xl font-black mt-2 text-brand-accent">0%</div></div>
            <div class="premium-card p-5">Accuracy: <div id="dash-avg-accuracy" class="text-3xl font-black mt-2 text-brand-teal">0%</div></div>
        </div>

        <div class="premium-card p-6 bg-white overflow-hidden">
            <h3 class="font-bold text-brand-dark mb-4">Student Database Records</h3>
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-brand-mint text-brand-tealDark uppercase bg-brand-bg">
                        <th class="py-3 px-4">Student</th>
                        <th class="py-3 px-4 text-center">Cohort</th>
                        <th class="py-3 px-4 text-center">Scenario</th>
                        <th class="py-3 px-4 text-center">Latency</th>
                        <th class="py-3 px-4 text-center">Efficiency</th>
                        <th class="py-3 px-4 text-center">Accuracy</th>
                    </tr>
                </thead>
                <tbody id="dash-student-table-body" class="divide-y divide-brand-bg"></tbody>
            </table>
        </div>
    </main>

    <script>
        // FSM Engine Definitions
        const triageFSM = {
            START_NODE: { prompt: "Scenario A: Compound distal leg fracture. What is next?", options: [ { text: "Assess respirations", nextState: "CHECK_RESPIRATIONS", optimal: true }, { text: "Check capillary refill", nextState: "RESULT_YELLOW", optimal: false } ] },
            CHECK_RESPIRATIONS: { prompt: "Patient is breathing rapidly at 34/min. Action?", options: [ { text: "Tag IMMEDIATE (RED)", nextState: "RESULT_RED", optimal: true }, { text: "Check pulse", nextState: "RESULT_YELLOW", optimal: false } ] },
            RESULT_RED: { isTerminal: true, tagColor: "bg-triage-red", textColor: "text-red-100", tagName: "Immediate (Red Tag)", prompt: "Correct! >30 breaths/min requires Red tag." },
            RESULT_YELLOW: { isTerminal: true, tagColor: "bg-triage-yellow", textColor: "text-yellow-900", tagName: "Delayed (Incorrect)", prompt: "Incorrect flow applied." },
            SCENARIO_B_START: { prompt: "Scenario B: Structural fire. Patient walks to you. Action?", options: [ { text: "Tag MINOR (GREEN) as walking wounded", nextState: "RESULT_GREEN", optimal: true }, { text: "Check respirations", nextState: "RESULT_YELLOW", optimal: false } ] },
            RESULT_GREEN: { isTerminal: true, tagColor: "bg-triage-green", textColor: "text-green-100", tagName: "Minor (Green Tag)", prompt: "Correct! Walking wounded are Green." }
        };

        // LARAVEL DATA INJECTION
        // This injects the database rows passed from your TriageController.
        const appState = {
            studentRecords: {!! json_encode($studentRecords ?? []) !!}
        };

        // Variables
        let currentState = 'START_NODE';
        let nodeDisplayTime = 0, latencyTimer = null, deviations = 0;
        let currentSession = { id: "", name: "", cohort: "", scenarioName: "", cumulativeLatency: 0, steps: 0, optimalSteps: 0 };

        // Navigation
        function showView(viewId) {
            document.querySelectorAll('.view-frame').forEach(v => v.classList.remove('active'));
            document.getElementById(viewId).classList.add('active');
            lucide.createIcons();
        }

        function executeLogin() { refreshDashboard(); showView('dashboardView'); }
        function toggleHelpModal() { document.getElementById('helpModal').classList.toggle('hidden'); }
        function confirmExitSimulation() { document.getElementById('exitConfirmModal').classList.remove('hidden'); }
        function closeExitModal() { document.getElementById('exitConfirmModal').classList.add('hidden'); }
        function executeExitSimulation() { clearInterval(latencyTimer); closeExitModal(); showView('homeView'); }
        function openSUSModal() { document.getElementById('susModal').classList.remove('hidden'); }
        function closeSUSModal() { document.getElementById('susModal').classList.add('hidden'); alert("SUS Saved."); showView('homeView'); }

        // Start Simulator
        function startStudentMode() {
            const scenarioSelect = document.getElementById('studentInputScenario');
            currentState = scenarioSelect.value;
            deviations = 0;
            currentSession = {
                id: document.getElementById('studentInputId').value || "STU" + Math.floor(Math.random() * 9000),
                name: document.getElementById('studentInputName').value || "Live Participant",
                cohort: document.getElementById('studentInputCohort').value,
                scenarioName: scenarioSelect.options[scenarioSelect.selectedIndex].text.split(':')[0],
                cumulativeLatency: 0, steps: 0, optimalSteps: 0
            };
            document.getElementById('telemetryName').innerText = currentSession.name;
            document.getElementById('scenarioTitle').innerText = currentSession.scenarioName;
            showView('studentView');
            renderFSMNode();
        }

        // Render Engine & LARAVEL SAVING
        function renderFSMNode() {
            const nodeData = triageFSM[currentState];
            document.getElementById('telemetryState').innerText = currentState;
            document.getElementById('nodePrompt').innerText = nodeData.prompt;
            const optionsWrapper = document.getElementById('optionsWrapper');
            optionsWrapper.innerHTML = '';
            
            nodeDisplayTime = Date.now();
            if (latencyTimer) clearInterval(latencyTimer);
            latencyTimer = setInterval(() => { document.getElementById('telemetryLatency').innerText = ((Date.now() - nodeDisplayTime) / 1000).toFixed(2) + 's'; }, 60);

            if (nodeData.isTerminal) {
                clearInterval(latencyTimer);
                let finalLatency = parseFloat(currentSession.cumulativeLatency.toFixed(1));
                let finalEfficiency = Math.max(100 - (deviations * 35), 15);
                let finalAccuracy = Math.round((currentSession.optimalSteps / currentSession.steps) * 100) || 0;

                const payload = {
                    student_id: currentSession.id,
                    student_name: currentSession.name,
                    cohort: currentSession.cohort,
                    scenario: currentSession.scenarioName,
                    latency: finalLatency === 0 ? 4.5 : finalLatency,
                    efficiency: finalEfficiency,
                    accuracy: finalAccuracy
                };

                // LARAVEL AJAX POST TO DATABASE
                fetch('/api/sessions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                }).then(response => response.json()).then(data => {
                    appState.studentRecords.push(payload); // Update UI
                    refreshDashboard();
                }).catch(err => console.error(err));

                optionsWrapper.innerHTML = `
                    <div class="p-6 rounded-xl ${nodeData.tagColor} ${nodeData.textColor} text-center space-y-4"><h4 class="text-2xl font-black">${nodeData.tagName}</h4></div>
                    <button onclick="openSUSModal()" class="w-full mt-4 py-3 bg-brand-teal text-white rounded-xl">Complete SUS Survey</button>
                `;
                return;
            }

            nodeData.options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = "w-full text-left p-4 bg-white border-2 border-brand-mint hover:bg-brand-mintLight rounded-xl text-brand-dark mb-2";
                btn.innerText = opt.text;
                btn.onclick = () => {
                    currentSession.cumulativeLatency += parseFloat(((Date.now() - nodeDisplayTime) / 1000).toFixed(2));
                    currentSession.steps++;
                    if (opt.optimal) { currentSession.optimalSteps++; } else {
                        deviations++;
                        document.getElementById('telemetryEfficiency').innerText = Math.max(100 - (deviations * 35), 15) + '%';
                    }
                    currentState = opt.nextState;
                    renderFSMNode();
                };
                optionsWrapper.appendChild(btn);
            });
        }

        // Dashboard Rendering
        function refreshDashboard() {
            const records = appState.studentRecords;
            document.getElementById('dash-total-students').innerText = records.length;
            if(records.length === 0) return;

            let lat = 0, eff = 0, acc = 0;
            records.forEach(r => { lat += parseFloat(r.latency); eff += r.efficiency; acc += r.accuracy; });
            
            document.getElementById('dash-avg-latency').innerText = (lat / records.length).toFixed(1) + 's';
            document.getElementById('dash-avg-efficiency').innerText = Math.round(eff / records.length) + '%';
            document.getElementById('dash-avg-accuracy').innerText = Math.round(acc / records.length) + '%';

            const tbody = document.getElementById('dash-student-table-body');
            tbody.innerHTML = '';
            [...records].reverse().forEach(r => {
                tbody.innerHTML += `<tr>
                    <td class="py-4 px-4 font-bold text-sm">${r.student_name || r.name}<br><span class="text-xs font-normal text-brand-muted">${r.student_id || r.id}</span></td>
                    <td class="text-center text-sm">${r.cohort}</td>
                    <td class="text-center text-sm">${r.scenario}</td>
                    <td class="text-center font-bold">${r.latency}s</td>
                    <td class="text-center">${r.efficiency}%</td>
                    <td class="text-center">${r.accuracy}%</td>
                </tr>`;
            });
        }

        function exportToCSV() {
            let csv = "Student ID,Name,Cohort,Scenario,Latency,Efficiency,Accuracy\n";
            appState.studentRecords.forEach(r => { csv += `${r.student_id || r.id},"${r.student_name || r.name}","${r.cohort}","${r.scenario}",${r.latency},${r.efficiency},${r.accuracy}\n`; });
            let link = document.createElement("a");
            link.href = encodeURI("data:text/csv;charset=utf-8," + csv);
            link.download = "TriageSim_Export.csv";
            link.click();
        }

        window.onload = () => { refreshDashboard(); lucide.createIcons(); };
    </script>
</body>
</html>