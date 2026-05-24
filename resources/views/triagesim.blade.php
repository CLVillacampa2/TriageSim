<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Laravel Security Token for Database Saves -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>TriageSim - Interactive First Aid and Triage Learning Platform</title>
    
    <!-- Tailwind CSS for modern layouts, typography and utility classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            bg: '#eef9f5',
                            mint: '#d1f2e9',
                            mintLight: '#e0f4f0',
                            teal: '#0096a5',
                            tealDark: '#087a86',
                            tealDeep: '#065f68',
                            accent: '#00a887',
                            dark: '#1e293b',
                            muted: '#64748b'
                        },
                        triage: {
                            red: '#ef4444',
                            yellow: '#eab308',
                            green: '#22c55e',
                            black: '#1e293b'
                        }
                    }
                }
            }
        }
    </script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-color: #eef9f5;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        .premium-card {
            background: #ffffff;
            border: 2px solid #d1f2e9;
            border-radius: 16px;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -4px rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 150, 165, 0.15);
            border-color: #0096a5;
        }

        /* Animated transitions between views */
        .view-frame {
            display: none;
            animation: slideUpFade 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .view-frame.active {
            display: block;
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Elegant scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #eef9f5;
        }
        ::-webkit-scrollbar-thumb {
            background: #c3eade;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0096a5;
        }
    </style>
</head>
<body class="min-height-screen flex flex-col items-center justify-center p-4 md:p-8">

    <!-- ================= BRAND / HEADER NAVIGATION ================= -->
    <header class="w-full max-w-6xl mb-8 flex items-center justify-between">
        <div class="flex items-center gap-3 cursor-pointer" onclick="showView('homeView')">
            <div class="w-10 h-10 bg-brand-accent rounded-full flex items-center justify-center text-white shadow-md shadow-brand-accent/20">
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

    <!-- ================= HELP & PROTOCOL MODAL ================= -->
    <div id="helpModal" class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border-2 border-brand-mint max-w-lg w-full overflow-hidden shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <div class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white flex justify-between items-center">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <i data-lucide="book-open" class="w-5 h-5"></i> START Triage Reference Sheet
                </h3>
                <button onclick="toggleHelpModal()" class="text-white/80 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 text-sm text-brand-dark overflow-y-auto max-h-[70vh]">
                <p class="font-medium text-brand-tealDark">Simple Triage and Rapid Treatment (START) Algorithm Rules:</p>
                <div class="space-y-3">
                    <div class="p-3 bg-red-50 border-l-4 border-triage-red rounded-r-lg">
                        <strong class="text-triage-red block">Immediate (Red Tag)</strong>
                        Respirations &gt; 30/min, Capillary Refill Time &gt; 2 seconds (or absent radial pulse), or unable to follow simple commands.
                    </div>
                    <div class="p-3 bg-yellow-50 border-l-4 border-triage-yellow rounded-r-lg">
                        <strong class="text-triage-yellow block">Delayed (Yellow Tag)</strong>
                        Spontaneous respirations present, Rate &lt; 30/min, perfusion metrics within normal limits (CRT &lt; 2s), but unable to walk.
                    </div>
                    <div class="p-3 bg-green-50 border-l-4 border-triage-green rounded-r-lg">
                        <strong class="text-triage-green block">Minor (Green Tag)</strong>
                        All patients who can walk (the "walking wounded"). High mobility indicators.
                    </div>
                    <div class="p-3 bg-slate-50 border-l-4 border-triage-black rounded-r-lg">
                        <strong class="text-brand-dark block">Deceased (Black Tag)</strong>
                        No spontaneous ventilation, even after manual airway opening.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= CUSTOM EXIT CONFIRMATION MODAL ================= -->
    <div id="exitConfirmModal" class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border-2 border-brand-mint max-w-sm w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <h3 class="text-lg font-bold text-brand-dark mb-2 flex items-center gap-2 text-red-500">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i> Exit Simulation?
            </h3>
            <p class="text-sm text-brand-muted mb-6">
                Are you sure you want to quit this scenario? Your current progress and telemetry metrics will be lost.
            </p>
            <div class="flex gap-3">
                <button onclick="closeExitModal()" class="flex-1 py-2.5 px-4 border-2 border-brand-mint text-brand-tealDark font-bold rounded-xl hover:bg-brand-bg transition-colors text-sm">
                    Cancel
                </button>
                <button onclick="executeExitSimulation()" class="flex-1 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors text-sm">
                    Exit Scenario
                </button>
            </div>
        </div>
    </div>

    <!-- ================= SYSTEM USABILITY SCALE (SUS) MODAL ================= -->
    <div id="susModal" class="fixed inset-0 bg-brand-dark/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl border-2 border-brand-mint max-w-md w-full p-6 shadow-2xl animate-[slideUpFade_0.2s_ease-out]">
            <h3 class="text-lg font-bold text-brand-dark mb-2 flex items-center gap-2 text-brand-teal">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i> System Usability Scale (SUS)
            </h3>
            <p class="text-xs text-brand-muted mb-4 pb-4 border-b border-brand-bg">
                Methodology Step 4: Please rate the platform's student-friendliness. (Scale: 1 = Strongly Disagree, 5 = Strongly Agree).
            </p>
            <div class="space-y-4 mb-6">
                <div>
                    <p class="text-sm font-semibold text-brand-dark mb-2">1. I think that I would like to use this system frequently.</p>
                    <div class="flex justify-between gap-2">
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs">1</button>
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs">2</button>
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs">3</button>
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs">4</button>
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded bg-brand-teal text-white transition-colors text-xs">5</button>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-semibold text-brand-dark mb-2">2. I found the system unnecessarily complex.</p>
                    <div class="flex justify-between gap-2">
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded bg-brand-teal text-white transition-colors text-xs">1</button>
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs">2</button>
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs">3</button>
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs">4</button>
                        <button onclick="selectSUS(this)" class="flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs">5</button>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="closeSUSModal()" class="w-full py-3 px-4 bg-brand-accent hover:bg-brand-tealDark text-white font-bold rounded-xl transition-colors text-sm">
                    Submit Survey Data
                </button>
            </div>
        </div>
    </div>

    <!-- ================= VIEW 1: HOME VIEW ================= -->
    <main id="homeView" class="view-frame active w-full max-w-5xl">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-brand-dark mb-4">
                Branching-Scenario <span class="text-brand-teal">Emergency Triage</span>
            </h1>
            <p class="text-lg text-brand-muted max-w-2xl mx-auto font-medium">
                Bridging the clinical theory-practice gap through interactive Finite State Machine (FSM) models and precise decision latency telemetry.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Student Module Card -->
            <div class="premium-card premium-card-hover p-8 cursor-pointer flex flex-col justify-between" onclick="showView('studentSetupView')">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-brand-mintLight flex items-center justify-center text-brand-teal mb-6">
                        <i data-lucide="play-circle" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-brand-dark mb-3">Student Simulator</h2>
                    <p class="text-brand-muted mb-6 leading-relaxed">
                        Enter a safe-to-fail clinical scenario. Your decision accuracy, path variations, and hesitation patterns will be tracked dynamically.
                    </p>
                </div>
                <div class="space-y-3 pt-4 border-t border-brand-mintLight">
                    <div class="flex items-center gap-2.5 text-sm text-brand-dark font-medium">
                        <i data-lucide="activity" class="w-4 h-4 text-brand-teal"></i> Real-time Latency (L) Telemetry
                    </div>
                    <div class="flex items-center gap-2.5 text-sm text-brand-dark font-medium">
                        <i data-lucide="git-branch" class="w-4 h-4 text-brand-teal"></i> Multi-path Branching Architecture
                    </div>
                </div>
            </div>

            <!-- Instructor Analytics Card -->
            <div class="premium-card premium-card-hover p-8 cursor-pointer flex flex-col justify-between" onclick="showView('loginView')">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-brand-mintLight flex items-center justify-center text-brand-teal mb-6">
                        <i data-lucide="line-chart" class="w-8 h-8"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-brand-dark mb-3">Instructor Dashboard</h2>
                    <p class="text-brand-muted mb-6 leading-relaxed">
                        Access detailed learning analytics, map structural bottleneck heatmaps, and assess individual/aggregate path optimization metrics.
                    </p>
                </div>
                <div class="space-y-3 pt-4 border-t border-brand-mintLight">
                    <div class="flex items-center gap-2.5 text-sm text-brand-dark font-medium">
                        <i data-lucide="users" class="w-4 h-4 text-brand-teal"></i> Collective Class Rank Lists
                    </div>
                    <div class="flex items-center gap-2.5 text-sm text-brand-dark font-medium">
                        <i data-lucide="flame" class="w-4 h-4 text-brand-teal"></i> Cognitive Hesitation Heatmaps
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-brand-mint text-xs font-semibold tracking-wider text-brand-tealDark uppercase rounded-full shadow-sm">
                <i data-lucide="database" class="w-3.5 h-3.5"></i> FSM State engine active
            </span>
        </div>
    </main>

    <!-- ================= VIEW 1.5: OPTIONAL STUDENT SETUP VIEW ================= -->
    <main id="studentSetupView" class="view-frame w-full max-w-md">
        <button onclick="showView('homeView')" class="mb-6 flex items-center gap-2 text-brand-tealDark hover:text-brand-teal font-semibold transition-colors text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Main Menu
        </button>

        <div class="premium-card overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <i data-lucide="user-cog" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold leading-tight">Student Profile Setup</h2>
                    <p class="text-xs text-brand-mintLight">Provide details for cohort logging (Optional)</p>
                </div>
            </div>

            <div class="p-6 space-y-5 bg-white">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Student Name <span class="text-brand-muted text-[10px] lowercase">(optional)</span></label>
                    <input type="text" id="studentInputName" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all placeholder-brand-muted/50" placeholder="e.g. John Doe">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Student ID <span class="text-brand-muted text-[10px] lowercase">(optional)</span></label>
                    <input type="text" id="studentInputId" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all placeholder-brand-muted/50" placeholder="e.g. STU2026118">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Cohort / Section</label>
                    <select id="studentInputCohort" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all bg-white">
                        <option value="NURS-301-A">Section NURS-301-A (Mon/Wed)</option>
                        <option value="NURS-301-B">Section NURS-301-B (Tue/Thu)</option>
                        <option value="Independent">Independent Learner</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Methodology Clinical Scenario</label>
                    <select id="studentInputScenario" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all bg-white">
                        <option value="START_NODE">Scenario A: Mass Casualty (Trauma/Respiratory)</option>
                        <option value="SCENARIO_B_START">Scenario B: Structural Fire (Ambulatory Check)</option>
                    </select>
                </div>

                <div class="p-3 bg-brand-bg border border-brand-mint rounded-xl text-xs text-brand-muted flex items-start gap-2.5">
                    <i data-lucide="info" class="w-4 h-4 text-brand-teal shrink-0 mt-0.5"></i>
                    <span>Leaving fields blank will automatically assign a mock profile so you can proceed immediately.</span>
                </div>

                <button onclick="startStudentMode()" class="w-full bg-brand-accent hover:bg-brand-tealDark text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-brand-accent/20 transition-all">
                    <span>Launch Simulator</span>
                    <i data-lucide="play" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </main>

    <!-- ================= VIEW 2: INSTRUCTOR LOGIN VIEW ================= -->
    <main id="loginView" class="view-frame w-full max-w-md">
        <button onclick="showView('homeView')" class="mb-6 flex items-center gap-2 text-brand-tealDark hover:text-brand-teal font-semibold transition-colors text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Main Menu
        </button>

        <div class="premium-card overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-brand-teal to-brand-tealDark text-white flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <i data-lucide="shield-alert" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold leading-tight">Instructor Login</h2>
                    <p class="text-xs text-brand-mintLight">Access administrative research metrics</p>
                </div>
            </div>

            <div class="p-6 space-y-5 bg-white">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Academic Email</label>
                    <input type="email" id="loginEmail" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all" value="p.williams@university.edu">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-brand-dark mb-2">Password</label>
                    <input type="password" id="loginPassword" class="w-full px-4 py-3 border-2 border-brand-mint rounded-xl text-brand-dark font-medium focus:border-brand-teal outline-none transition-all" value="password123">
                </div>

                <button onclick="executeLogin()" class="w-full bg-brand-teal hover:bg-brand-tealDark text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-brand-teal/20 transition-all">
                    <span>Authenticate Account</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>

                <!-- Accounts Selector / Fast Demo Box -->
                <div class="p-4 bg-brand-bg border border-brand-mint rounded-xl space-y-2">
                    <span class="block text-xs font-bold text-brand-tealDark uppercase tracking-wider">Demo Access Profiles</span>
                    <div class="text-xs space-y-1 text-brand-dark">
                        <p class="font-semibold">p.williams@university.edu <span class="font-normal text-brand-muted">(Dr. Patricia Williams)</span></p>
                        <p class="font-semibold">r.kim@university.edu <span class="font-normal text-brand-muted">(Prof. Robert Kim)</span></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ================= VIEW 3: STUDENT SIMULATOR VIEW ================= -->
    <main id="studentView" class="view-frame w-full max-w-5xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-brand-dark flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-brand-teal animate-pulse"></span>
                    Clinical Simulation Terminal
                </h1>
                <p class="text-sm text-brand-muted">Execute optimal protocols rapidly to optimize Path Efficiency.</p>
            </div>
            <button onclick="confirmExitSimulation()" class="self-start md:self-auto px-4 py-2 border-2 border-brand-mint bg-white text-brand-tealDark font-bold rounded-xl hover:bg-brand-mintLight transition-colors flex items-center gap-2 text-sm">
                <i data-lucide="log-out" class="w-4 h-4"></i> Exit Scenario
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left & Main FSM Interactive Case Engine -->
            <div class="lg:col-span-2 space-y-6">
                <div class="premium-card p-6 bg-white space-y-6">
                    <div class="flex items-center justify-between border-b border-brand-bg pb-4">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="clipboard-list" class="text-brand-teal w-5 h-5"></i>
                            <span id="scenarioTitle" class="font-bold text-brand-dark">Active Case: Disaster Area Alpha</span>
                        </div>
                        <span class="px-3 py-1 bg-brand-mintLight text-brand-tealDark font-bold text-xs rounded-full uppercase tracking-wider">START Protocol</span>
                    </div>

                    <!-- Scenario Patient/Stage Box -->
                    <div class="space-y-4">
                        <span class="text-xs font-bold text-brand-tealDark uppercase tracking-wider block">Clinical Presentation / Prompt</span>
                        <div id="nodePrompt" class="p-5 bg-brand-bg rounded-xl text-brand-dark font-medium border-l-4 border-brand-teal text-base leading-relaxed">
                            Loading triage assessment data...
                        </div>
                    </div>

                    <!-- Scenario Action Interactive Inputs -->
                    <div class="space-y-4">
                        <span class="text-xs font-bold text-brand-tealDark uppercase tracking-wider block">Determine Next Step</span>
                        <div id="optionsWrapper" class="flex flex-col gap-3">
                            <!-- Dynamic scenario selection buttons are drawn here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Telemetry Panel -->
            <div class="space-y-6">
                <div class="premium-card p-6 bg-white space-y-5">
                    <h3 class="text-md font-bold text-brand-dark border-b border-brand-bg pb-3 flex items-center gap-2">
                        <i data-lucide="gauge" class="w-5 h-5 text-brand-teal"></i> Active Case Telemetry
                    </h3>

                    <div class="space-y-3.5">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Participant Name:</span>
                            <span id="telemetryName" class="font-bold text-brand-dark text-xs">Live Participant</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Clinical FSM Node:</span>
                            <span id="telemetryState" class="font-mono text-brand-teal font-bold bg-brand-bg px-2.5 py-0.5 rounded-lg text-xs">START_NODE</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Decision Latency (L):</span>
                            <span id="telemetryLatency" class="font-bold text-red-500 font-mono text-base">0.00s</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Protocol Deviations (E-Penalty):</span>
                            <span id="telemetryDeviations" class="font-bold text-brand-dark bg-brand-bg w-7 h-7 flex items-center justify-center rounded-full text-xs">0</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-brand-muted">Path Efficiency (E):</span>
                            <span id="telemetryEfficiency" class="font-bold text-brand-accent text-base">100%</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-brand-bg text-[11px] text-brand-muted leading-relaxed">
                        <p class="font-semibold text-brand-dark mb-1">How telemetry is scored:</p>
                        Decision Latency (L) is tracked continuously. Taking incorrect paths (deviations) penalizes overall Path Efficiency (E) instantly.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ================= VIEW 4: INSTRUCTOR DASHBOARD VIEW ================= -->
    <main id="dashboardView" class="view-frame w-full max-w-6xl">
        <!-- Top Custom Brand Nav -->
        <div class="premium-card p-5 bg-white mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand-teal rounded-full flex items-center justify-center text-white">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="font-bold text-brand-dark">Performance Analytics Panel</h2>
                    <span class="text-xs text-brand-muted">Active Research Cohort: Sections Combined</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="exportToCSV()" class="px-4 py-2 bg-brand-accent hover:bg-brand-tealDark text-white font-bold rounded-xl text-sm transition-colors flex items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> Export to Excel/SPSS
                </button>
                <div class="text-right hidden md:block border-l border-brand-mint pl-4 ml-2">
                    <p class="text-sm font-bold text-brand-dark">Dr. Patricia Williams</p>
                    <p class="text-xs text-brand-muted">Emergency Dept Advisor</p>
                </div>
                <button onclick="showView('homeView')" class="px-4 py-2 bg-brand-bg hover:bg-brand-mint text-brand-tealDark font-bold rounded-xl text-sm transition-colors flex items-center gap-2">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                </button>
            </div>
        </div>

        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-brand-dark">Performance Dashboard</h1>
            <p class="text-sm text-brand-muted">Aggregate analytics computed from branching FSM state logs</p>
        </div>

        <!-- Metrics Cards Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="premium-card p-5 bg-white flex flex-col justify-between min-h-[120px]">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-muted">
                    <i data-lucide="users" class="w-4 h-4 text-brand-teal"></i> Total Participants
                </div>
                <div>
                    <div id="dash-total-students" class="text-3xl font-black text-brand-dark leading-tight mt-2">0</div>
                    <span class="text-[11px] text-brand-muted font-medium">Recorded live submissions</span>
                </div>
            </div>

            <div class="premium-card p-5 bg-white flex flex-col justify-between min-h-[120px]">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-muted">
                    <i data-lucide="hourglass" class="w-4 h-4 text-brand-teal"></i> Avg Decision Latency (L)
                </div>
                <div>
                    <div id="dash-avg-latency" class="text-3xl font-black text-brand-dark leading-tight mt-2 text-red-500">0s</div>
                    <span class="text-[11px] text-brand-muted font-medium">Standard baseline threshold is &lt; 5s</span>
                </div>
            </div>

            <div class="premium-card p-5 bg-white flex flex-col justify-between min-h-[120px]">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-muted">
                    <i data-lucide="award" class="w-4 h-4 text-brand-teal"></i> Mean Path Efficiency (E)
                </div>
                <div>
                    <div id="dash-avg-efficiency" class="text-3xl font-black text-brand-dark leading-tight mt-2 text-brand-accent">0%</div>
                    <span class="text-[11px] text-brand-muted font-medium">Ratio of actual to optimal steps</span>
                </div>
            </div>

            <div class="premium-card p-5 bg-white flex flex-col justify-between min-h-[120px]">
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-brand-muted">
                    <i data-lucide="shield" class="w-4 h-4 text-brand-teal"></i> Diagnostic Accuracy
                </div>
                <div>
                    <div id="dash-avg-accuracy" class="text-3xl font-black text-brand-dark leading-tight mt-2 text-brand-teal font-sans">0%</div>
                    <span class="text-[11px] text-brand-muted font-medium">Optimal first-try accuracy</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Left Heatmap & Hesitation Column -->
            <div class="premium-card p-6 bg-white lg:col-span-2 space-y-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                        <i data-lucide="flame" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-brand-dark">Clinical Decision Cognitive Heatmap</h3>
                        <p class="text-xs text-brand-muted">Tracks hesitation metrics and cognitive friction points</p>
                    </div>
                </div>

                <div class="space-y-4 pt-2" id="heatmapContainer">
                    <!-- Dynamic progression heatmap indicators based on node responses -->
                </div>
            </div>

            <!-- Right Student Rankings List -->
            <div class="premium-card p-6 bg-white space-y-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <i data-lucide="trophy" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-brand-dark">Student Performance Ranking</h3>
                        <p class="text-xs text-brand-muted">Sort of top paths (Efficiency &amp; Speed)</p>
                    </div>
                </div>

                <div id="dash-ranking-list" class="space-y-3.5">
                    <!-- Top ranks rendered on runtime calculation -->
                </div>
            </div>
        </div>

        <!-- Individual Student Progress Log Table -->
        <div class="premium-card p-6 bg-white overflow-hidden">
            <div class="flex items-center justify-between border-b border-brand-bg pb-4 mb-4">
                <div>
                    <h3 class="font-bold text-brand-dark">Individual Student Data Records</h3>
                    <p class="text-xs text-brand-muted">Fully logged FSM transition variables</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-brand-mint text-xs font-bold text-brand-tealDark uppercase bg-brand-bg">
                            <th class="py-3 px-4">Student ID / Name</th>
                            <th class="py-3 px-4 text-center">Cohort</th>
                            <th class="py-3 px-4 text-center">Scenario Tested</th>
                            <th class="py-3 px-4 text-center">Avg Latency (s)</th>
                            <th class="py-3 px-4 text-center">Path Efficiency</th>
                            <th class="py-3 px-4 text-center">Diagnostic Accuracy</th>
                        </tr>
                    </thead>
                    <tbody id="dash-student-table-body" class="divide-y divide-brand-bg">
                        <!-- Student data records injected instantly -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- ================= SCENARIOS & ENGINE CODE DATA ================= -->
    <!-- Safe Data Island to prevent 'Unexpected token !' SyntaxErrors in Javascript -->
    <script id="laravel-student-data" type="application/json">
        {!! json_encode($studentRecords ?? []) !!}
    </script>

    <script>
        // Finite State Machine (FSM) Scenarios and Clinical Routing Maps
        const triageFSM = {
            START_NODE: {
                prompt: "Emergency Triage Area: You encounter a patient on-scene presenting with a compound distal leg fracture. Massive bleeding is controlled via local compression bandage. What is your next protocol investigation stage?",
                options: [
                    { text: "Assess the patient's spontaneous respiratory status", nextState: "CHECK_RESPIRATIONS", optimal: true },
                    { text: "Measure peripheral perfusion via capillary refill time", nextState: "CHECK_PERFUSION", optimal: false },
                    { text: "Request the patient follow basic command signals ('Squeeze my hand')", nextState: "CHECK_MENTAL", optimal: false }
                ]
            },
            CHECK_RESPIRATIONS: {
                prompt: "You lean in close to evaluate respiratory movement. The patient is breathing spontaneously, but rapid tachypnea is noted, at 34 breaths per minute. Applying the START standard, what is the triage action?",
                options: [
                    { text: "Immediately tag patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED", optimal: true },
                    { text: "Ignore the breathing rate and check peripheral pulse/capillary refill", nextState: "CHECK_PERFUSION", optimal: false },
                    { text: "Assess cognitive response by questioning standard commands", nextState: "CHECK_MENTAL", optimal: false }
                ]
            },
            CHECK_PERFUSION: {
                prompt: "You palpate the distal radial pulse and assess capillary refill. Radial pulse is extremely thready. CRT is measured at 3.6 seconds (Standard START ceiling is 2s). What is the appropriate triage outcome?",
                options: [
                    { text: "Immediately classify patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED", optimal: true },
                    { text: "Execute cognitive command assessments to confirm mental state", nextState: "CHECK_MENTAL", optimal: false }
                ]
            },
            CHECK_MENTAL: {
                prompt: "The patient can look in your direction but presents severe cognitive disorientation, failing to follow hand squeezing instructions. What is the appropriate triage designation?",
                options: [
                    { text: "Classify patient as IMMEDIATE (RED TAG)", nextState: "RESULT_RED", optimal: true },
                    { text: "Classify patient as DELAYED (YELLOW TAG)", nextState: "RESULT_YELLOW", optimal: false }
                ]
            },
            RESULT_RED: {
                isTerminal: true,
                tagColor: "bg-triage-red",
                textColor: "text-red-100",
                tagName: "Immediate (Red Tag)",
                prompt: "Assessment Complete: Based on START triage rules, this patient exhibits severe respiratory/perfusion/mental status disruption, warranting priority care. Your diagnostic pathway is clinically accurate!"
            },
            RESULT_YELLOW: {
                isTerminal: true,
                tagColor: "bg-triage-yellow",
                textColor: "text-yellow-900",
                tagName: "Delayed (Yellow Tag)",
                prompt: "Assessment Complete: However, your classification is incorrect. A patient showing respiratory rate >30/min, poor perfusion, or mental confusion cannot be tagged yellow. They require an immediate RED tag."
            },
            SCENARIO_B_START: {
                prompt: "Emergency Triage Area (Scenario B): A structural fire has occurred. A patient approaches you walking, coughing slightly, complaining of a burned arm. What is the immediate START protocol action?",
                options: [
                    { text: "Direct the patient to the designated 'Walking Wounded' collection area", nextState: "RESULT_GREEN", optimal: true },
                    { text: "Assess respiratory rate immediately to ensure airway isn't compromised", nextState: "SCENARIO_B_WRONG", optimal: false },
                    { text: "Check capillary refill time on the unburned arm", nextState: "SCENARIO_B_WRONG", optimal: false }
                ]
            },
            RESULT_GREEN: {
                isTerminal: true,
                tagColor: "bg-triage-green",
                textColor: "text-green-100",
                tagName: "Minor (Green Tag)",
                prompt: "Assessment Complete: By confirming the patient can walk, START protocol dictates immediate classification as MINOR. They are grouped into the 'walking wounded' before further respiratory checks."
            },
            SCENARIO_B_WRONG: {
                isTerminal: true,
                tagColor: "bg-triage-yellow",
                textColor: "text-yellow-900",
                tagName: "Delayed (Incorrect Flow)",
                prompt: "Assessment Complete: Incorrect protocol execution. In START triage, if a patient is ambulatory (able to walk), they are immediately tagged GREEN without proceeding down the RPM (Respirations, Perfusion, Mental Status) checklist."
            }
        };

        // Safe helper function to parse backend records in pure HTML fallback or Laravel environment
        function getInitialRecords() {
            try {
                const rawData = document.getElementById('laravel-student-data').textContent.trim();
                
                // If it contains Blade tags, we are in a pure HTML preview without a PHP server
                if (rawData.includes('{!!') || rawData.includes('json_encode') || rawData === '') {
                    return [
                        { id: "STU2024001", student_id: "STU2024001", student_name: "Sarah Martinez", name: "Sarah Martinez", cohort: "NURS-301-A", scenario: "Scenario A: Mass Casualty", latency: 6.4, efficiency: 95, accuracy: 100 }
                    ];
                }
                return JSON.parse(rawData);
            } catch (e) {
                console.warn("Laravel data parse failed, loading mocks.");
                return [
                    { id: "STU2024001", student_id: "STU2024001", student_name: "Sarah Martinez", name: "Sarah Martinez", cohort: "NURS-301-A", scenario: "Scenario A: Mass Casualty", latency: 6.4, efficiency: 95, accuracy: 100 }
                ];
            }
        }

        // App States & Database Synchronization payload
        const appState = {
            studentRecords: getInitialRecords(),
            hesitationCount: {
                node_1: 12,
                node_2: 21,
                node_3: 8
            }
        };

        // Session Variables
        let currentState = 'START_NODE';
        let nodeDisplayTime = 0;
        let latencyTimer = null;
        let deviations = 0;
        let currentSession = {
            id: "",
            name: "",
            cohort: "",
            scenarioName: "",
            cumulativeLatency: 0,
            steps: 0,
            optimalSteps: 0
        };

        // View Transition Handler
        function showView(viewId) {
            document.querySelectorAll('.view-frame').forEach(view => {
                view.classList.remove('active');
            });
            const activeView = document.getElementById(viewId);
            if (activeView) {
                activeView.classList.add('active');
            }
            lucide.createIcons();
        }

        // Login authentication simulator
        function executeLogin() {
            const email = document.getElementById('loginEmail').value.trim();
            if (email) {
                refreshDashboard();
                showView('dashboardView');
            }
        }

        // Help Modal toggle
        function toggleHelpModal() {
            const modal = document.getElementById('helpModal');
            modal.classList.toggle('hidden');
        }

        // Exit simulation custom alert trigger
        function confirmExitSimulation() {
            const modal = document.getElementById('exitConfirmModal');
            if (modal) {
                modal.classList.remove('hidden');
                lucide.createIcons();
            }
        }

        // Close exit confirmation modal
        function closeExitModal() {
            const modal = document.getElementById('exitConfirmModal');
            if (modal) modal.classList.add('hidden');
        }

        // Handle the final exit callback action
        function executeExitSimulation() {
            if (latencyTimer) clearInterval(latencyTimer);
            closeExitModal();
            showView('homeView');
        }

        // Handle SUS Form Methods
        function selectSUS(button) {
            // Highlight selected scale answer
            const parent = button.parentElement;
            parent.querySelectorAll('button').forEach(btn => {
                btn.className = "flex-1 py-2 border border-brand-mint rounded hover:bg-brand-teal hover:text-white transition-colors text-xs";
            });
            button.className = "flex-1 py-2 border border-brand-mint rounded bg-brand-teal text-white transition-colors text-xs";
        }

        function openSUSModal() {
            const modal = document.getElementById('susModal');
            if (modal) {
                modal.classList.remove('hidden');
                lucide.createIcons();
            }
        }

        function closeSUSModal() {
            const modal = document.getElementById('susModal');
            if (modal) {
                modal.classList.add('hidden');
                showView('homeView');
            }
        }

        // Export Data to CSV for Excel/SPSS Methodology step
        function exportToCSV() {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Student ID,Name,Cohort,Scenario,Decision Latency (Seconds),Path Efficiency (%),Accuracy (%)\n";
            
            appState.studentRecords.forEach(function(r) {
                // Support both PHP models and fallback state values
                let id = r.student_id ? r.student_id : r.id;
                let name = r.student_name ? r.student_name : r.name;
                let scenario = r.scenario ? r.scenario : 'Scenario A';
                
                let row = `${id},"${name}","${r.cohort}","${scenario}",${r.latency},${r.efficiency},${r.accuracy}`;
                csvContent += row + "\n";
            });

            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "TriageSim_Methodology_Export.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Initialize Student Mode Session using optional setups
        function startStudentMode() {
            // Retrieve chosen scenario from form (Defaults to START_NODE)
            const scenarioSelect = document.getElementById('studentInputScenario');
            currentState = scenarioSelect ? scenarioSelect.value : 'START_NODE';
            
            // Extract the readable text (e.g. "Scenario A: Mass Casualty")
            const fullScenarioText = scenarioSelect ? scenarioSelect.options[scenarioSelect.selectedIndex].text : "Scenario A: Mass Casualty";
            const shortScenarioTitle = fullScenarioText.split(' (')[0]; 
            
            deviations = 0;

            // Optional Inputs retrieval
            const inputName = document.getElementById('studentInputName').value.trim();
            const inputId = document.getElementById('studentInputId').value.trim();
            const inputCohort = document.getElementById('studentInputCohort').value;

            currentSession = {
                id: inputId || "STU" + Math.floor(100000 + Math.random() * 900000),
                name: inputName || "Live Participant " + Math.floor(10 + Math.random() * 89),
                cohort: inputCohort || "NURS-301-A",
                scenarioName: shortScenarioTitle,
                cumulativeLatency: 0,
                steps: 0,
                optimalSteps: 0
            };

            // Set Telemetry & UI Headers
            document.getElementById('telemetryName').innerText = currentSession.name;
            document.getElementById('scenarioTitle').innerText = `Active Case: ${currentSession.scenarioName}`;
            document.getElementById('telemetryEfficiency').innerText = "100%";
            document.getElementById('telemetryDeviations').innerText = "0";

            // Reset setup input values for next session
            document.getElementById('studentInputName').value = '';
            document.getElementById('studentInputId').value = '';

            showView('studentView');
            renderFSMNode();
        }

        // Render current FSM Node Prompt & Option Selection
        function renderFSMNode() {
            const nodeData = triageFSM[currentState];
            document.getElementById('telemetryState').innerText = currentState;
            document.getElementById('nodePrompt').innerText = nodeData.prompt;

            const optionsWrapper = document.getElementById('optionsWrapper');
            optionsWrapper.innerHTML = '';

            // Set up timers and tracking
            nodeDisplayTime = Date.now();
            if (latencyTimer) clearInterval(latencyTimer);

            latencyTimer = setInterval(() => {
                let timeElapsed = ((Date.now() - nodeDisplayTime) / 1000).toFixed(2);
                document.getElementById('telemetryLatency').innerText = timeElapsed + 's';
            }, 60);

            // Check if terminal/result node
            if (nodeData.isTerminal) {
                clearInterval(latencyTimer);
                document.getElementById('telemetryLatency').innerText = "Stopped";

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

                // LARAVEL AJAX SECURE PERSISTENCE
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                if (csrfMeta && csrfMeta.getAttribute('content')) {
                    fetch('/api/sessions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfMeta.getAttribute('content')
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Inject local data structure for instant dashboard listing
                        appState.studentRecords.push(payload);
                        refreshDashboard();
                    })
                    .catch(err => console.error("Database connection exception: ", err));
                } else {
                    // Safe local mode fallback for pure HTML runtime environments
                    appState.studentRecords.push(payload);
                    refreshDashboard();
                }

                // Update hesitation stats to simulate dynamic heatmap shift
                if (deviations > 0) {
                    appState.hesitationCount.node_2 += 5;
                } else {
                    appState.hesitationCount.node_1 += 2;
                }

                // Draw Terminal Results Box
                optionsWrapper.innerHTML = `
                    <div class="p-6 rounded-xl ${nodeData.tagColor} ${nodeData.textColor} shadow-lg text-center space-y-4 animate-pulse">
                        <span class="text-xs uppercase font-extrabold tracking-wider bg-white/20 px-3 py-1 rounded-full inline-block">Final Decision Classification</span>
                        <h4 class="text-2xl font-black">${nodeData.tagName}</h4>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button onclick="openSUSModal()" class="flex-1 py-3 px-4 bg-brand-teal hover:bg-brand-tealDark text-white font-bold rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i> Complete SUS Survey
                        </button>
                        <button onclick="showView('homeView')" class="flex-1 py-3 px-4 bg-white border-2 border-brand-mint text-brand-tealDark font-bold rounded-xl hover:bg-brand-mintLight transition-colors">
                            Return Home
                        </button>
                    </div>
                `;
                lucide.createIcons();
                return;
            }

            // Draw selection options with routing maps
            nodeData.options.forEach(opt => {
                const button = document.createElement('button');
                button.className = "w-full text-left p-4 bg-white border-2 border-brand-mint hover:border-brand-teal rounded-xl text-brand-dark font-medium hover:bg-brand-mintLight transition-all flex justify-between items-center group";
                button.innerHTML = `
                    <span>${opt.text}</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-brand-teal opacity-0 group-hover:opacity-100 transition-all"></i>
                `;
                button.onclick = () => selectOption(opt.nextState, opt.optimal);
                optionsWrapper.appendChild(button);
            });
            lucide.createIcons();
        }

        // Process step transition logic & accumulate metrics
        function selectOption(nextStateID, isOptimal) {
            let latencyElapsed = parseFloat(((Date.now() - nodeDisplayTime) / 1000).toFixed(2));
            currentSession.cumulativeLatency += latencyElapsed;
            currentSession.steps++;

            if (isOptimal) {
                currentSession.optimalSteps++;
            } else {
                deviations++;
                document.getElementById('telemetryDeviations').innerText = deviations;
                let currentEff = Math.max(100 - (deviations * 35), 15);
                document.getElementById('telemetryEfficiency').innerText = currentEff + '%';
            }

            currentState = nextStateID;
            renderFSMNode();
        }

        // Recompute Dashboard Metrics & Render lists/tables
        function refreshDashboard() {
            const records = appState.studentRecords;
            document.getElementById('dash-total-students').innerText = records.length;

            if (records.length === 0) return;

            let totalLat = 0, totalEff = 0, totalAcc = 0;
            records.forEach(r => {
                totalLat += parseFloat(r.latency);
                totalEff += parseInt(r.efficiency);
                totalAcc += parseInt(r.accuracy);
            });

            const avgLat = (totalLat / records.length).toFixed(1);
            const avgEff = Math.round(totalEff / records.length);
            const avgAcc = Math.round(totalAcc / records.length);

            document.getElementById('dash-avg-latency').innerText = avgLat + 's';
            document.getElementById('dash-avg-efficiency').innerHTML = `<span class="px-2 py-0.5 bg-brand-bg rounded-lg text-brand-accent">${avgEff}%</span>`;
            document.getElementById('dash-avg-accuracy').innerText = avgAcc + '%';

            // Check if standard decision latency baseline is exceeded for color alert styling
            if (parseFloat(avgLat) <= 6.0) {
                document.getElementById('dash-avg-latency').className = "text-3xl font-black text-brand-accent leading-tight mt-2";
            } else {
                document.getElementById('dash-avg-latency').className = "text-3xl font-black text-red-500 leading-tight mt-2";
            }

            // Sync Table Records
            const tbody = document.getElementById('dash-student-table-body');
            tbody.innerHTML = '';
            [...records].reverse().forEach(r => {
                // Shorten the scenario name slightly so it fits beautifully in the dashboard
                const name = r.student_name ? r.student_name : r.name;
                const id = r.student_id ? r.student_id : r.id;
                const scenario = r.scenario ? r.scenario : 'Scenario A';
                const shortScenario = scenario.split(':')[0]; 
                
                tbody.innerHTML += `
                    <tr class="hover:bg-brand-bg/40 transition-colors">
                        <td class="py-4 px-4">
                            <p class="font-bold text-brand-dark text-sm">${name}</p>
                            <span class="text-xs text-brand-muted font-mono">${id}</span>
                        </td>
                        <td class="py-4 px-4 text-center font-semibold text-sm text-brand-muted">${r.cohort}</td>
                        <td class="py-4 px-4 text-center font-semibold text-xs text-brand-tealDeep"><span class="bg-brand-mintLight px-2 py-1 rounded-md">${shortScenario}</span></td>
                        <td class="py-4 px-4 text-center font-bold text-sm ${r.latency <= 5.5 ? 'text-brand-accent' : 'text-red-500'}">${r.latency}s</td>
                        <td class="py-4 px-4 text-center font-bold text-sm ${r.efficiency >= 80 ? 'text-brand-accent' : 'text-yellow-600'}">${r.efficiency}%</td>
                        <td class="py-4 px-4 text-center font-bold text-sm ${r.accuracy >= 80 ? 'text-brand-teal' : 'text-red-500'}">${r.accuracy}%</td>
                    </tr>
                `;
            });

            // Sync Leaderboard Ranking List (Sorted by higher efficiency and faster latency)
            const sorted = [...records].sort((a, b) => {
                if (b.efficiency !== a.efficiency) return b.efficiency - a.efficiency;
                return a.latency - b.latency;
            });

            const rankingList = document.getElementById('dash-ranking-list');
            rankingList.innerHTML = '';
            sorted.slice(0, 3).forEach((r, idx) => {
                let badgeStyle = "bg-yellow-400 text-yellow-900";
                if (idx === 1) badgeStyle = "bg-slate-300 text-slate-800";
                if (idx === 2) badgeStyle = "bg-amber-600 text-amber-100";

                const name = r.student_name ? r.student_name : r.name;

                rankingList.innerHTML += `
                    <div class="flex items-center justify-between p-3.5 bg-brand-bg border border-brand-mint rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 ${badgeStyle} rounded-full font-bold flex items-center justify-center text-xs shadow-sm">${idx + 1}</div>
                            <div>
                                <p class="text-xs font-bold text-brand-dark">${name}</p>
                                <p class="text-[10px] text-brand-muted">${r.accuracy}% Accuracy | ${r.efficiency}% Efficiency</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-brand-teal">${r.latency}s</span>
                    </div>
                `;
            });

            // Sync Heatmap Bars
            const h1 = Math.min(appState.hesitationCount.node_1, 100);
            const h2 = Math.min(appState.hesitationCount.node_2, 100);
            const h3 = Math.min(appState.hesitationCount.node_3, 100);

            document.getElementById('heatmapContainer').innerHTML = `
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-brand-dark">
                        <span>node-1: Assess Breathing (START Opening)</span>
                        <span class="text-brand-muted">${h1}% hesitation avg</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-brand-teal h-full" style="width: ${h1}%"></div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-brand-dark">
                        <span>node-2: Check Respiratory Rate Rate-limit</span>
                        <span class="font-bold ${h2 > 20 ? 'text-red-500' : 'text-brand-muted'}">${h2}% hesitation avg</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-orange-500 h-full" style="width: ${h2}%"></div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-bold text-brand-dark">
                        <span>node-3: Check Capillary Refill Time (CRT)</span>
                        <span class="text-brand-muted">${h3}% hesitation avg</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-brand-accent h-full" style="width: ${h3}%"></div>
                    </div>
                </div>
            `;
            lucide.createIcons();
        }

        // Auto-run dashboard update & initialize icons on boot
        window.onload = function() {
            refreshDashboard();
            lucide.createIcons();
        }
    </script>
</body>
</html>