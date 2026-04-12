import React, { useState, useEffect, useRef, useCallback } from "react";
import AdminLayout from "../layout";
import { Head, Link, router } from "@inertiajs/react";
import ApexChart from "@/Components/Base/ApexChart";
import {
    Users, MousePointer2, TrendingUp, Cpu, Globe, Activity,
    RefreshCw, ShieldCheck, Search, X, Info, Clock, Zap,
    ArrowUpRight, Monitor, Smartphone, Tablet, AlertTriangle,
    CheckCircle, Navigation, Radio, BarChart2, Map, Eye,
    Target, GitBranch, Layers, Timer, ChevronRight, Wifi,
} from "lucide-react";

// ── Tab IDs ────────────────────────────────────────────────────────────────
const TABS = [
    { id: "overview",  label: "Overview",  icon: BarChart2 },
    { id: "realtime",  label: "Real-Time", icon: Radio    },
    { id: "behavior",  label: "Behavior",  icon: Target   },
];

export default function TrafficAnalyticsPage({ stats }) {
    const {
        visits_over_time, device_distribution, browser_distribution,
        os_distribution, top_pages, referrer_sources, heatmap,
        status_codes, geo_breakdown, top_entry_pages,
        response_time_trend, recent_logs, summary, filters,
    } = stats;

    const [activeTab,    setActiveTab]    = useState("overview");
    const [isRefreshing, setIsRefreshing] = useState(false);
    const [searchTerm,   setSearchTerm]   = useState(filters.uri || "");
    const [selectedLog,  setSelectedLog]  = useState(null);

    // Real-time state
    const [rtData,       setRtData]       = useState(null);
    const [rtLoading,    setRtLoading]    = useState(false);
    const [rtError,      setRtError]      = useState(null);
    const [rtLastUpdate, setRtLastUpdate] = useState(null);
    const rtIntervalRef = useRef(null);

    // ── Debounced search ───────────────────────────────────────────────────
    useEffect(() => {
        const t = setTimeout(() => {
            if (searchTerm !== (filters.uri || "")) {
                handleFilterChange("uri", searchTerm);
            }
        }, 500);
        return () => clearTimeout(t);
    }, [searchTerm]);

    // ── Real-time polling ──────────────────────────────────────────────────
    const fetchRealtime = useCallback(async () => {
        setRtLoading(true);
        setRtError(null);
        try {
            const res = await fetch("/admin/traffic/realtime?minutes=5");
            if (!res.ok) throw new Error("Failed to fetch real-time data");
            const data = await res.json();
            setRtData(data);
            setRtLastUpdate(new Date());
        } catch (e) {
            setRtError(e.message);
        } finally {
            setRtLoading(false);
        }
    }, []);

    useEffect(() => {
        if (activeTab === "realtime") {
            fetchRealtime();
            rtIntervalRef.current = setInterval(fetchRealtime, 15000); // 15s polling
        } else {
            clearInterval(rtIntervalRef.current);
        }
        return () => clearInterval(rtIntervalRef.current);
    }, [activeTab, fetchRealtime]);

    // ── Filter change ──────────────────────────────────────────────────────
    const handleFilterChange = (key, value) => {
        setIsRefreshing(true);
        router.get("/admin/traffic", { ...filters, [key]: value }, {
            preserveState: true, preserveScroll: true,
            onFinish: () => setIsRefreshing(false),
        });
    };

    // ── Chart colour palette ───────────────────────────────────────────────
    const PALETTE = ["#6366f1", "#10b981", "#f59e0b", "#ef4444", "#8b5cf6", "#ec4899", "#14b8a6", "#f97316"];

    // ── Visitor Trends Chart ───────────────────────────────────────────────
    const visitorsChartOpts = {
        chart: { id: "visitors-chart", toolbar: { show: false }, fontFamily: "Inter, sans-serif", background: "transparent" },
        dataLabels: { enabled: false },
        stroke: { curve: "smooth", width: [3, 2], dashArray: [0, 6] },
        colors: ["#6366f1", "#10b981"],
        fill: { type: ["gradient", "solid"], gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.01, stops: [0, 100] } },
        xaxis: {
            categories: visits_over_time.map((d) => d.date),
            axisBorder: { show: false }, axisTicks: { show: false },
            labels: { style: { colors: "#94a3b8", fontSize: "11px" } },
        },
        yaxis: { labels: { style: { colors: "#94a3b8", fontSize: "11px" } } },
        grid: { borderColor: "#f1f5f9", strokeDashArray: 4, xaxis: { lines: { show: false } } },
        tooltip: { theme: "light", x: { format: "dd MMM" } },
        legend: { position: "top", horizontalAlign: "right", offsetY: -10 },
    };

    const visitorsSeries = [
        { name: "Page Views", data: visits_over_time.map((d) => d.total_visits) },
        { name: "Sessions",   data: visits_over_time.map((d) => d.sessions || d.unique_visits) },
    ];

    // ── Response Time Chart ────────────────────────────────────────────────
    const rtChartOpts = {
        chart: { id: "rt-chart", toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
        dataLabels: { enabled: false },
        stroke: { curve: "smooth", width: 2 },
        colors: ["#f59e0b", "#ef4444"],
        xaxis: {
            categories: response_time_trend.map((d) => d.date),
            axisBorder: { show: false }, axisTicks: { show: false },
            labels: { style: { colors: "#94a3b8", fontSize: "11px" } },
        },
        yaxis: { labels: { style: { colors: "#94a3b8", fontSize: "11px" }, formatter: (v) => `${v}ms` } },
        grid: { borderColor: "#f1f5f9", strokeDashArray: 4 },
        tooltip: { theme: "light", y: { formatter: (v) => `${v} ms` } },
    };
    const rtSeries = [
        { name: "Avg Response", data: response_time_trend.map((d) => d.avg_ms) },
        { name: "Max Response", data: response_time_trend.map((d) => d.max_ms) },
    ];

    // ── Donut charts ───────────────────────────────────────────────────────
    const makeDonut = (id, labels, series) => ({
        chart: { id, toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
        labels,
        colors: PALETTE,
        stroke: { show: false },
        dataLabels: { enabled: false },
        legend: { show: false },
        plotOptions: { pie: { donut: { size: "72%", labels: { show: true, total: { show: true, label: "Total", color: "#94a3b8", formatter: () => series.reduce((a, b) => a + b, 0) } } } } },
    });

    const deviceDonutOpts = makeDonut("device-chart", device_distribution.map((d) => d.device_type), device_distribution.map((d) => d.count));
    const deviceSeries    = device_distribution.map((d) => d.count);

    const newReturnOpts = makeDonut("new-return-chart", ["New Visitors", "Returning"], [summary.new_visitors, summary.returning_visitors]);
    const newReturnSeries = [summary.new_visitors, summary.returning_visitors];

    // ── Browser horizontal bar ─────────────────────────────────────────────
    const browserBarOpts = {
        chart: { id: "browser-bar", toolbar: { show: false }, fontFamily: "Inter, sans-serif" },
        plotOptions: { bar: { horizontal: true, barHeight: "60%", borderRadius: 4 } },
        colors: ["#6366f1"],
        dataLabels: { enabled: false },
        xaxis: { categories: browser_distribution.map((b) => b.browser), labels: { style: { colors: "#94a3b8", fontSize: "11px" } } },
        yaxis: { labels: { style: { colors: "#6b7280", fontSize: "12px", fontWeight: 600 } } },
        grid: { borderColor: "#f1f5f9", xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
        tooltip: { theme: "light" },
    };
    const browserBarSeries = [{ name: "Visits", data: browser_distribution.map((b) => b.count) }];

    // ── Referrer pie ───────────────────────────────────────────────────────
    const referrerTotal = referrer_sources.reduce((s, r) => s + r.count, 0);

    // ── Real-time sparkline ────────────────────────────────────────────────
    const sparkOpts = {
        chart: { id: "spark", toolbar: { show: false }, sparkline: { enabled: true } },
        stroke: { curve: "smooth", width: 2 },
        colors: ["#6366f1"],
        fill: { type: "gradient", gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0, stops: [0, 100] } },
        tooltip: { enabled: true, theme: "dark", x: { show: true } },
    };

    return (
        <AdminLayout>
            <Head title="Traffic Analytics" />
            <div className="max-w-7xl mx-auto space-y-5 md:pb-12">

                {/* ── Top Bar ───────────────────────────────────────────────── */}
                <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <div className="flex items-center gap-3">
                        <div className="p-2.5 bg-indigo-50 rounded-xl border border-indigo-100">
                            <Activity size={20} className="text-indigo-600" />
                        </div>
                        <div>
                            <h1 className="text-xl font-bold text-gray-900 flex items-center gap-2">
                                Analytics Console
                                {isRefreshing && <RefreshCw size={14} className="animate-spin text-indigo-500" />}
                            </h1>
                            <p className="text-[10px] text-gray-400 font-semibold uppercase tracking-widest mt-0.5">
                                {filters.days === 0 ? "All Time" : `Last ${filters.days} Days`} • Public Routes
                            </p>
                        </div>
                    </div>

                    <div className="flex flex-wrap items-center gap-3">
                        {/* Search */}
                        <div className="relative flex-1 min-w-[200px]">
                            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={14} />
                            <input
                                type="text"
                                placeholder="Search URI path…"
                                className="w-full pl-9 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 transition-all"
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                            />
                        </div>

                        {/* Date presets */}
                        <div className="flex bg-gray-50 p-1 rounded-xl border border-gray-100">
                            {[1, 7, 30, 90, 0].map((d) => (
                                <button
                                    key={d}
                                    onClick={() => handleFilterChange("days", d)}
                                    className={`px-3 py-1.5 text-xs font-bold rounded-lg transition-all ${
                                        filters.days === d
                                            ? "bg-white text-indigo-600 shadow-sm"
                                            : "text-gray-400 hover:text-gray-600"
                                    }`}
                                >
                                    {d === 0 ? "All" : d === 1 ? "1D" : `${d}D`}
                                </button>
                            ))}
                        </div>

                        {/* Bot toggle */}
                        <button
                            onClick={() => handleFilterChange("is_bot", !filters.is_bot)}
                            className={`flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold transition-all border ${
                                filters.is_bot
                                    ? "bg-violet-50 border-violet-100 text-violet-600"
                                    : "bg-white border-gray-100 text-gray-400 hover:text-gray-600"
                            }`}
                        >
                            <ShieldCheck size={14} />
                            {filters.is_bot ? "Bots ON" : "Bots OFF"}
                        </button>
                    </div>
                </div>

                {/* ── Tab Navigation ────────────────────────────────────────── */}
                <div className="flex gap-1 bg-gray-100 p-1 rounded-2xl w-fit">
                    {TABS.map(({ id, label, icon: Icon }) => (
                        <button
                            key={id}
                            onClick={() => setActiveTab(id)}
                            className={`flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all ${
                                activeTab === id
                                    ? "bg-white text-gray-900 shadow-sm"
                                    : "text-gray-500 hover:text-gray-700"
                            }`}
                        >
                            <Icon size={15} />
                            {label}
                            {id === "realtime" && (
                                <span className="flex h-2 w-2">
                                    <span className="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-rose-400 opacity-75"></span>
                                    <span className="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                </span>
                            )}
                        </button>
                    ))}
                </div>

                {/* ═══════════════════════════════════════════════════════════
                    TAB 1: OVERVIEW
                ═══════════════════════════════════════════════════════════ */}
                {activeTab === "overview" && (
                    <div className="space-y-5">
                        {/* Summary KPI row */}
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <KpiCard title="Page Views"     value={summary.total_page_views}  icon={<MousePointer2 size={16} />} color="indigo"  />
                            <KpiCard title="Unique IPs"     value={summary.unique_visitors}   icon={<Users size={16} />}         color="emerald" />
                            <KpiCard title="Sessions"       value={summary.total_sessions}    icon={<Layers size={16} />}        color="sky"     />
                            <KpiCard title="Avg Response"   value={`${summary.avg_response_time}ms`} icon={<Timer size={16} />} color="amber"   />
                            <KpiCard title="4xx Errors"     value={summary.errors_4xx}        icon={<AlertTriangle size={16} />} color="red"     />
                            <KpiCard title="5xx Errors"     value={summary.errors_5xx}        icon={<AlertTriangle size={16} />} color="rose"    />
                        </div>

                        {/* Secondary KPIs */}
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <MetricTile label="Bounce Rate"        value={`${summary.bounce_rate}%`}           sub="sessions with 1 page" />
                            <MetricTile label="Pages / Session"    value={summary.avg_pages_per_session}       sub="avg depth"            />
                            <MetricTile label="New Visitors"       value={summary.new_visitors}                sub="first time today"     />
                            <MetricTile label="Detected Bots"      value={summary.bot_traffic}                 sub="filtered traffic"     />
                        </div>

                        {/* Main chart + Device donut */}
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-5">
                            <div className="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <div className="flex items-center justify-between mb-6">
                                    <h3 className="font-bold text-gray-900">Visitor Trends</h3>
                                    <div className="flex gap-4">
                                        <Legend color="#6366f1" label="Page Views" />
                                        <Legend color="#10b981" label="Sessions"   />
                                    </div>
                                </div>
                                <ApexChart options={visitorsChartOpts} series={visitorsSeries} type="area" height={280} />
                            </div>

                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
                                <h3 className="font-bold text-gray-900 mb-1 text-center">Devices</h3>
                                <p className="text-[10px] text-gray-400 font-semibold uppercase tracking-widest text-center mb-4">Distribution</p>
                                <ApexChart options={deviceDonutOpts} series={deviceSeries} type="donut" height={200} />
                                <div className="grid grid-cols-1 gap-2 mt-4">
                                    {device_distribution.map((item, i) => (
                                        <div key={item.device_type} className="flex items-center justify-between px-3 py-2 rounded-xl bg-gray-50">
                                            <div className="flex items-center gap-2">
                                                <div className="w-2.5 h-2.5 rounded-full" style={{ backgroundColor: PALETTE[i % PALETTE.length] }} />
                                                <span className="text-xs font-semibold text-gray-700">{item.device_type}</span>
                                            </div>
                                            <span className="text-xs font-black text-gray-900">{item.count}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>

                        {/* New vs Returning + Referrer Sources + Status Codes */}
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
                            {/* New vs Returning */}
                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <h3 className="font-bold text-gray-900 mb-4">Visitor Type</h3>
                                <ApexChart options={newReturnOpts} series={newReturnSeries} type="donut" height={180} />
                                <div className="flex justify-around mt-4">
                                    <div className="text-center">
                                        <p className="text-xs font-bold text-gray-400 uppercase tracking-widest">New</p>
                                        <p className="text-2xl font-black text-indigo-600">{summary.new_visitors}</p>
                                    </div>
                                    <div className="w-px bg-gray-100" />
                                    <div className="text-center">
                                        <p className="text-xs font-bold text-gray-400 uppercase tracking-widest">Returning</p>
                                        <p className="text-2xl font-black text-emerald-600">{summary.returning_visitors}</p>
                                    </div>
                                </div>
                            </div>

                            {/* Referrer Sources */}
                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <h3 className="font-bold text-gray-900 mb-4">Traffic Sources</h3>
                                <div className="space-y-3">
                                    {referrer_sources.map((src, i) => {
                                        const pct = referrerTotal > 0 ? Math.round((src.count / referrerTotal) * 100) : 0;
                                        return (
                                            <div key={src.name}>
                                                <div className="flex items-center justify-between mb-1">
                                                    <span className="text-xs font-semibold text-gray-700">{src.name}</span>
                                                    <span className="text-xs font-black text-gray-900">{src.count} <span className="text-gray-400 font-medium">({pct}%)</span></span>
                                                </div>
                                                <div className="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                    <div className="h-full rounded-full transition-all duration-700" style={{ width: `${pct}%`, backgroundColor: PALETTE[i % PALETTE.length] }} />
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>

                            {/* Status Codes */}
                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <h3 className="font-bold text-gray-900 mb-4">HTTP Status Codes</h3>
                                <div className="space-y-3">
                                    {status_codes.map((sc) => {
                                        const statusConfig = {
                                            "2xx": { color: "bg-emerald-500", text: "text-emerald-700", label: "Success" },
                                            "3xx": { color: "bg-amber-500",   text: "text-amber-700",   label: "Redirects" },
                                            "4xx": { color: "bg-red-500",     text: "text-red-700",     label: "Client Errors" },
                                            "5xx": { color: "bg-rose-600",    text: "text-rose-700",    label: "Server Errors" },
                                        };
                                        const cfg = statusConfig[sc.group_code] || { color: "bg-gray-400", text: "text-gray-700", label: "Other" };
                                        return (
                                            <div key={sc.group_code} className="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                                                <div className="flex items-center gap-2.5">
                                                    <div className={`w-2 h-2 rounded-full ${cfg.color}`} />
                                                    <span className={`text-xs font-bold ${cfg.text}`}>{sc.group_code}</span>
                                                    <span className="text-[10px] text-gray-400">{cfg.label}</span>
                                                </div>
                                                <span className="text-sm font-black text-gray-900">{sc.count}</span>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>

                        {/* Browser Bar + Top Pages */}
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <h3 className="font-bold text-gray-900 mb-4">Browser Usage</h3>
                                <ApexChart options={browserBarOpts} series={browserBarSeries} type="bar" height={220} />
                            </div>

                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <div className="flex items-center justify-between mb-4">
                                    <h3 className="font-bold text-gray-900">Top Pages</h3>
                                    <Link href="/admin/traffic/logs" className="text-xs font-bold text-indigo-500 hover:text-indigo-700 flex items-center gap-1">
                                        View All <ChevronRight size={12} />
                                    </Link>
                                </div>
                                <div className="space-y-2">
                                    {top_pages.slice(0, 8).map((page, i) => {
                                        const max = top_pages[0]?.count || 1;
                                        const pct = Math.round((page.count / max) * 100);
                                        return (
                                            <div key={page.uri} className="flex items-center gap-3 group">
                                                <span className="text-[10px] w-4 font-black text-gray-300">{i + 1}</span>
                                                <div className="flex-1 min-w-0">
                                                    <div className="flex items-center justify-between mb-1">
                                                        <p className="text-xs font-semibold text-gray-700 truncate max-w-[220px]">{page.uri}</p>
                                                        <span className="text-xs font-black text-gray-900 ml-2 shrink-0">{page.count}</span>
                                                    </div>
                                                    <div className="h-1 bg-gray-100 rounded-full overflow-hidden">
                                                        <div className="h-full bg-indigo-500 rounded-full" style={{ width: `${pct}%` }} />
                                                    </div>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>

                        {/* Recent Logs */}
                        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div className="p-5 border-b border-gray-50 flex items-center justify-between">
                                <h3 className="font-bold text-gray-900">Recent Hits</h3>
                                <Link href="/admin/traffic/logs" className="text-xs font-bold text-indigo-500 hover:text-indigo-700 flex items-center gap-1">
                                    Full Log <ChevronRight size={12} />
                                </Link>
                            </div>
                            <div className="overflow-x-auto">
                                <table className="w-full text-left">
                                    <thead className="bg-gray-50/70 border-b border-gray-100">
                                        <tr>
                                            {["URI", "Status", "Browser / OS", "Device", "Response", "Time"].map((h) => (
                                                <th key={h} className="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">{h}</th>
                                            ))}
                                            <th className="px-5 py-3" />
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-50 text-sm">
                                        {recent_logs.slice(0, 10).map((log) => (
                                            <tr key={log.id} className="hover:bg-gray-50/50 transition-colors group">
                                                <td className="px-5 py-3 max-w-[180px]">
                                                    <p className="font-semibold text-gray-800 truncate">{log.uri}</p>
                                                    {log.country_name && (
                                                        <p className="text-[10px] text-gray-400">{log.country_name}</p>
                                                    )}
                                                </td>
                                                <td className="px-5 py-3">
                                                    <StatusBadge code={log.status_code} />
                                                </td>
                                                <td className="px-5 py-3">
                                                    <span className="text-xs font-semibold text-gray-700">{log.browser}</span>
                                                    <span className="text-[10px] text-gray-400 ml-1">/ {log.os}</span>
                                                </td>
                                                <td className="px-5 py-3">
                                                    <DeviceIcon type={log.device_type} />
                                                </td>
                                                <td className="px-5 py-3">
                                                    <span className={`text-xs font-bold ${log.response_time > 1000 ? "text-red-500" : log.response_time > 500 ? "text-amber-500" : "text-emerald-600"}`}>
                                                        {log.response_time ? `${log.response_time}ms` : "–"}
                                                    </span>
                                                </td>
                                                <td className="px-5 py-3 text-[10px] text-gray-400 font-semibold">
                                                    {new Date(log.created_at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}
                                                </td>
                                                <td className="px-5 py-3 text-right">
                                                    <button onClick={() => setSelectedLog(log)} className="p-1.5 text-gray-300 hover:text-indigo-500 hover:bg-indigo-50 rounded-lg transition-all opacity-0 group-hover:opacity-100">
                                                        <Info size={15} />
                                                    </button>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                )}

                {/* ═══════════════════════════════════════════════════════════
                    TAB 2: REAL-TIME
                ═══════════════════════════════════════════════════════════ */}
                {activeTab === "realtime" && (
                    <div className="space-y-5">
                        {/* Status bar */}
                        <div className="flex items-center justify-between bg-white px-5 py-3 rounded-2xl border border-gray-100 shadow-sm">
                            <div className="flex items-center gap-2 text-xs text-gray-500">
                                <span className="flex h-2 w-2 relative">
                                    <span className="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-rose-400 opacity-75" />
                                    <span className="relative inline-flex rounded-full h-2 w-2 bg-rose-500" />
                                </span>
                                <span className="font-semibold">Live — polling every 15s</span>
                                {rtLastUpdate && (
                                    <span className="text-gray-400">· Last updated {rtLastUpdate.toLocaleTimeString()}</span>
                                )}
                            </div>
                            <button onClick={fetchRealtime} disabled={rtLoading} className="flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 disabled:opacity-50">
                                <RefreshCw size={13} className={rtLoading ? "animate-spin" : ""} />
                                Refresh Now
                            </button>
                        </div>

                        {rtError && (
                            <div className="bg-red-50 border border-red-100 rounded-2xl p-4 text-sm text-red-600 font-medium flex items-center gap-2">
                                <AlertTriangle size={16} /> {rtError}
                            </div>
                        )}

                        {rtData ? (
                            <>
                                {/* Active visitors hero */}
                                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div className="sm:col-span-1 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white flex flex-col items-center justify-center shadow-lg shadow-indigo-500/20">
                                        <div className="flex h-3 w-3 mb-4 relative">
                                            <span className="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-white opacity-50" />
                                            <span className="relative inline-flex rounded-full h-3 w-3 bg-white" />
                                        </div>
                                        <p className="text-6xl font-black tracking-tight">{rtData.active_visitors}</p>
                                        <p className="text-indigo-200 text-sm font-semibold mt-2 uppercase tracking-widest">Active Now</p>
                                        <p className="text-indigo-300 text-xs mt-1">Last {rtData.window_minutes} minutes</p>
                                    </div>

                                    <div className="sm:col-span-2 grid grid-cols-3 gap-4">
                                        <RtKpi label="Req/sec"    value={rtData.req_per_second} icon={<Zap size={16} />}       color="amber" />
                                        <RtKpi label="Pages Live" value={rtData.active_pages?.length || 0} icon={<Eye size={16} />} color="sky" />
                                        <RtKpi label="Countries"  value={rtData.active_countries?.length || 0} icon={<Globe size={16} />} color="emerald" />
                                    </div>
                                </div>

                                {/* Sparkline (per-minute) */}
                                {rtData.per_minute?.length > 0 && (
                                    <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                        <h3 className="font-bold text-gray-900 mb-4">Traffic — Last 30 Minutes</h3>
                                        <ApexChart
                                            options={{
                                                ...sparkOpts,
                                                xaxis: { categories: rtData.per_minute.map((m) => m.minute), labels: { style: { colors: "#94a3b8", fontSize: "10px" } }, axisBorder: { show: false }, axisTicks: { show: false } },
                                                yaxis: { labels: { style: { colors: "#94a3b8" } } },
                                                grid: { borderColor: "#f1f5f9", strokeDashArray: 4 },
                                                chart: { ...sparkOpts.chart, sparkline: { enabled: false }, toolbar: { show: false } },
                                            }}
                                            series={[{ name: "Hits", data: rtData.per_minute.map((m) => m.hits) }]}
                                            type="area"
                                            height={160}
                                        />
                                    </div>
                                )}

                                <div className="grid grid-cols-1 lg:grid-cols-2 gap-5">
                                    {/* Active pages */}
                                    <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                        <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                            <Navigation size={15} className="text-indigo-500" /> Pages Being Viewed
                                        </h3>
                                        <div className="space-y-2">
                                            {rtData.active_pages?.map((pg) => (
                                                <div key={pg.uri} className="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-indigo-50 transition-colors">
                                                    <span className="text-xs font-semibold text-gray-700 truncate max-w-[260px]">{pg.uri}</span>
                                                    <span className="text-xs font-black text-indigo-600 ml-2 shrink-0">{pg.hits} hits</span>
                                                </div>
                                            ))}
                                            {(!rtData.active_pages || rtData.active_pages.length === 0) && (
                                                <p className="text-center py-8 text-sm text-gray-400">No activity in the last {rtData.window_minutes} minutes</p>
                                            )}
                                        </div>
                                    </div>

                                    {/* Active countries */}
                                    <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                        <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                            <Map size={15} className="text-emerald-500" /> Active Countries
                                        </h3>
                                        <div className="space-y-2">
                                            {rtData.active_countries?.map((c) => (
                                                <div key={c.country_code} className="flex items-center justify-between p-3 rounded-xl bg-gray-50">
                                                    <div className="flex items-center gap-2">
                                                        <span className="text-base">
                                                            {countryFlag(c.country_code)}
                                                        </span>
                                                        <span className="text-xs font-semibold text-gray-700">{c.country_name || c.country_code}</span>
                                                    </div>
                                                    <span className="text-xs font-black text-gray-900">{c.count}</span>
                                                </div>
                                            ))}
                                            {(!rtData.active_countries || rtData.active_countries.length === 0) && (
                                                <p className="text-center py-8 text-sm text-gray-400">No geo data available yet</p>
                                            )}
                                        </div>
                                    </div>
                                </div>

                                {/* Recent hits stream */}
                                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                                    <div className="p-5 border-b border-gray-50 flex items-center gap-2">
                                        <Wifi size={15} className="text-rose-500 animate-pulse" />
                                        <h3 className="font-bold text-gray-900">Live Hit Stream</h3>
                                    </div>
                                    <div className="overflow-x-auto">
                                        <table className="w-full text-sm">
                                            <thead className="bg-gray-50/70 border-b border-gray-100">
                                                <tr>
                                                    {["URI", "IP", "Country", "Browser", "Status", "Time"].map((h) => (
                                                        <th key={h} className="px-5 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">{h}</th>
                                                    ))}
                                                </tr>
                                            </thead>
                                            <tbody className="divide-y divide-gray-50">
                                                {rtData.recent_hits?.map((hit) => (
                                                    <tr key={hit.id} className="hover:bg-gray-50/50 transition-colors">
                                                        <td className="px-5 py-3 font-semibold text-gray-800 truncate max-w-[200px]">{hit.uri}</td>
                                                        <td className="px-5 py-3 text-gray-500 text-xs font-medium">{hit.ip_address}</td>
                                                        <td className="px-5 py-3 text-xs">
                                                            {hit.country_code ? (
                                                                <span className="flex items-center gap-1">{countryFlag(hit.country_code)} {hit.country_name || hit.country_code}</span>
                                                            ) : "–"}
                                                        </td>
                                                        <td className="px-5 py-3 text-xs text-gray-600">{hit.browser}</td>
                                                        <td className="px-5 py-3"><StatusBadge code={hit.status_code} /></td>
                                                        <td className="px-5 py-3 text-[10px] text-gray-400 font-semibold">
                                                            {new Date(hit.created_at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit", second: "2-digit" })}
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </>
                        ) : (
                            !rtError && (
                                <div className="flex items-center justify-center py-24">
                                    <RefreshCw size={28} className="animate-spin text-indigo-400" />
                                </div>
                            )
                        )}
                    </div>
                )}

                {/* ═══════════════════════════════════════════════════════════
                    TAB 3: BEHAVIOR
                ═══════════════════════════════════════════════════════════ */}
                {activeTab === "behavior" && (
                    <div className="space-y-5">
                        {/* Response Time Chart */}
                        {response_time_trend.length > 0 && (
                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <div className="flex items-center justify-between mb-4">
                                    <div>
                                        <h3 className="font-bold text-gray-900">Response Time Trend</h3>
                                        <p className="text-xs text-gray-400 mt-0.5">Avg & max request duration (ms)</p>
                                    </div>
                                    <div className="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-1.5">
                                        <Timer size={14} className="text-amber-600" />
                                        <span className="text-xs font-bold text-amber-700">{summary.avg_response_time}ms avg</span>
                                    </div>
                                </div>
                                <ApexChart options={rtChartOpts} series={rtSeries} type="line" height={220} />
                            </div>
                        )}

                        {/* Heatmap */}
                        <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <div className="flex items-center justify-between mb-4">
                                <div>
                                    <h3 className="font-bold text-gray-900">Traffic Heatmap</h3>
                                    <p className="text-xs text-gray-400 mt-0.5">Hourly intensity — Sun to Sat, 00:00–23:00</p>
                                </div>
                                <div className="flex items-center gap-2 text-[10px] text-gray-400">
                                    <span className="w-3 h-3 rounded bg-indigo-100 border border-indigo-200" /> Low
                                    <span className="w-3 h-3 rounded bg-indigo-400 border border-indigo-400 ml-1" /> Med
                                    <span className="w-3 h-3 rounded bg-indigo-700 border border-indigo-700 ml-1" /> High
                                </div>
                            </div>
                            <HeatmapGrid heatmap={heatmap} />
                        </div>

                        {/* OS + Geographic */}
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            {/* OS breakdown */}
                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <h3 className="font-bold text-gray-900 mb-4">OS Distribution</h3>
                                <div className="space-y-2">
                                    {os_distribution.map((item, i) => {
                                        const total = os_distribution.reduce((s, x) => s + x.count, 0);
                                        const pct = total > 0 ? Math.round((item.count / total) * 100) : 0;
                                        return (
                                            <div key={item.os}>
                                                <div className="flex justify-between text-xs mb-1">
                                                    <span className="font-semibold text-gray-700">{item.os}</span>
                                                    <span className="font-black text-gray-900">{item.count} <span className="text-gray-400">({pct}%)</span></span>
                                                </div>
                                                <div className="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                    <div className="h-full rounded-full" style={{ width: `${pct}%`, backgroundColor: PALETTE[i % PALETTE.length] }} />
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>

                            {/* Geographic breakdown */}
                            <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <Globe size={15} className="text-emerald-500" /> Top Countries
                                </h3>
                                <div className="space-y-2">
                                    {geo_breakdown.length > 0 ? (
                                        geo_breakdown.map((country, i) => {
                                            const maxCount = geo_breakdown[0]?.count || 1;
                                            const pct = Math.round((country.count / maxCount) * 100);
                                            return (
                                                <div key={country.country_code} className="flex items-center gap-3">
                                                    <span className="text-lg w-7 text-center">{countryFlag(country.country_code)}</span>
                                                    <div className="flex-1">
                                                        <div className="flex items-center justify-between mb-1">
                                                            <span className="text-xs font-semibold text-gray-700">{country.country_name || country.country_code}</span>
                                                            <span className="text-xs font-black text-gray-900">{country.count}</span>
                                                        </div>
                                                        <div className="h-1 bg-gray-100 rounded-full overflow-hidden">
                                                            <div className="h-full bg-emerald-500 rounded-full" style={{ width: `${pct}%` }} />
                                                        </div>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    ) : (
                                        <div className="text-center py-10 text-sm text-gray-400">
                                            <Globe size={28} className="mx-auto mb-2 opacity-20" />
                                            Geo data collecting — check back soon
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>

                        {/* Top Entry Pages */}
                        <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 className="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <GitBranch size={15} className="text-indigo-500" /> Top Entry Pages
                            </h3>
                            <div className="space-y-3">
                                {top_entry_pages.map((pg, i) => (
                                    <div key={pg.uri} className="flex items-center gap-4 p-3 rounded-xl bg-gray-50 hover:bg-indigo-50 transition-colors">
                                        <span className="text-xs font-black text-gray-300 w-5">#{i + 1}</span>
                                        <span className="flex-1 text-xs font-semibold text-gray-700 truncate">{pg.uri}</span>
                                        <div className="flex items-center gap-1 bg-indigo-100 text-indigo-700 px-2 py-1 rounded-lg">
                                            <Layers size={11} />
                                            <span className="text-[10px] font-black">{pg.sessions} sessions</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                )}
            </div>

            {/* ── Log Detail Modal ────────────────────────────────────────── */}
            {selectedLog && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md bg-black/20 animate-fadeIn">
                    <div className="bg-white rounded-3xl shadow-2xl border border-gray-100 max-w-lg w-full overflow-hidden animate-slideUp">
                        <div className="p-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-white">
                            <h3 className="font-bold text-gray-900">Visit Details</h3>
                            <button onClick={() => setSelectedLog(null)} className="p-2 hover:bg-white rounded-xl text-gray-400 hover:text-gray-700 transition-all">
                                <X size={18} />
                            </button>
                        </div>
                        <div className="p-6 space-y-5 overflow-y-auto max-h-[75vh]">
                            <div className="grid grid-cols-2 gap-x-8 gap-y-5 text-sm">
                                <DI label="URI PATH"   value={selectedLog.uri}           highlight />
                                <DI label="STATUS"     value={selectedLog.status_code}   color={selectedLog.status_code >= 400 ? "red" : "green"} />
                                <DI label="VISIT TIME" value={new Date(selectedLog.created_at).toLocaleString()} />
                                <DI label="RESPONSE"   value={selectedLog.response_time ? `${selectedLog.response_time}ms` : "–"} />
                                <DI label="IP ADDRESS" value={selectedLog.ip_address} />
                                <DI label="COUNTRY"    value={selectedLog.country_name || "Unknown"} />
                                <DI label="OS"         value={selectedLog.os} />
                                <DI label="BROWSER"    value={selectedLog.browser} />
                                <DI label="DEVICE"     value={selectedLog.device_type} />
                                <DI label="BOT?"       value={selectedLog.is_bot ? "Yes" : "No — Human"} color={selectedLog.is_bot ? "red" : "green"} />
                                <DI label="NEW VISITOR" value={selectedLog.is_new_visitor ? "Yes" : "Returning"} />
                                <DI label="REFERRER"   value={selectedLog.referrer || "Direct"} />
                            </div>
                            <div className="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Raw User Agent</p>
                                <p className="text-xs text-gray-500 font-mono break-all leading-relaxed">{selectedLog.user_agent}</p>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}

// ── Heatmap Grid ────────────────────────────────────────────────────────────
function HeatmapGrid({ heatmap }) {
    const days  = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    const hours = Array.from({ length: 24 }, (_, i) => i);

    const allCounts = Object.values(heatmap).flatMap((row) => Object.values(row));
    const maxCount  = Math.max(...allCounts, 1);

    const cellColor = (count) => {
        if (count === 0) return "bg-gray-100";
        const intensity = count / maxCount;
        if (intensity < 0.2) return "bg-indigo-100";
        if (intensity < 0.4) return "bg-indigo-200";
        if (intensity < 0.6) return "bg-indigo-400";
        if (intensity < 0.8) return "bg-indigo-600";
        return "bg-indigo-800";
    };

    return (
        <div className="overflow-x-auto">
            <div className="min-w-[650px]">
                {/* Hour labels */}
                <div className="flex mb-1 ml-10">
                    {hours.map((h) => (
                        <div key={h} className="flex-1 text-center text-[9px] font-bold text-gray-300">
                            {h % 3 === 0 ? `${h}:00` : ""}
                        </div>
                    ))}
                </div>
                {days.map((day, d) => (
                    <div key={day} className="flex items-center gap-1 mb-1">
                        <span className="text-[10px] font-bold text-gray-400 w-8 shrink-0">{day}</span>
                        {hours.map((h) => {
                            const count = heatmap[d]?.[h] ?? 0;
                            return (
                                <div
                                    key={h}
                                    className={`flex-1 h-5 rounded-sm ${cellColor(count)} cursor-default transition-opacity hover:opacity-80 group relative`}
                                    title={`${day} ${h}:00 — ${count} hits`}
                                />
                            );
                        })}
                    </div>
                ))}
            </div>
        </div>
    );
}

// ── Helper Components ───────────────────────────────────────────────────────
function KpiCard({ title, value, icon, color }) {
    const c = {
        indigo:  "bg-indigo-50 text-indigo-600 border-indigo-100",
        emerald: "bg-emerald-50 text-emerald-600 border-emerald-100",
        sky:     "bg-sky-50 text-sky-600 border-sky-100",
        amber:   "bg-amber-50 text-amber-600 border-amber-100",
        red:     "bg-red-50 text-red-600 border-red-100",
        rose:    "bg-rose-50 text-rose-600 border-rose-100",
    };
    return (
        <div className="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-start gap-3 hover:shadow-md transition-all">
            <div className={`p-2 rounded-xl border ${c[color]}`}>{icon}</div>
            <div className="min-w-0">
                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest truncate">{title}</p>
                <p className="text-xl font-black text-gray-900 tracking-tight mt-0.5">{value ?? 0}</p>
            </div>
        </div>
    );
}

function MetricTile({ label, value, sub }) {
    return (
        <div className="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm text-center hover:shadow-md transition-all">
            <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{label}</p>
            <p className="text-3xl font-black text-gray-900 mt-1 tracking-tight">{value ?? 0}</p>
            <p className="text-[10px] text-gray-400 mt-0.5">{sub}</p>
        </div>
    );
}

function RtKpi({ label, value, icon, color }) {
    const c = { amber: "text-amber-600 bg-amber-50", sky: "text-sky-600 bg-sky-50", emerald: "text-emerald-600 bg-emerald-50" };
    return (
        <div className="bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center p-4 gap-2">
            <div className={`p-2 rounded-xl ${c[color]}`}>{icon}</div>
            <p className="text-2xl font-black text-gray-900">{value}</p>
            <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{label}</p>
        </div>
    );
}

function StatusBadge({ code }) {
    if (!code) return <span className="text-gray-300">–</span>;
    const c =
        code < 300  ? "bg-emerald-50 text-emerald-700 border-emerald-100" :
        code < 400  ? "bg-amber-50 text-amber-700 border-amber-100" :
        code < 500  ? "bg-red-50 text-red-700 border-red-100" :
                      "bg-rose-50 text-rose-800 border-rose-100";
    return <span className={`px-1.5 py-0.5 rounded-md text-[10px] font-black border ${c}`}>{code}</span>;
}

function DeviceIcon({ type }) {
    if (type === "Mobile")  return <Smartphone size={13} className="text-amber-500" />;
    if (type === "Tablet")  return <Tablet     size={13} className="text-sky-500"   />;
    return                          <Monitor    size={13} className="text-gray-400"  />;
}

function DI({ label, value, highlight, color }) {
    const tc = { green: "text-emerald-600", red: "text-red-600", blue: "text-indigo-600" };
    return (
        <div className="space-y-1">
            <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{label}</p>
            <p className={`font-bold break-words ${highlight ? "text-indigo-600" : "text-gray-800"} ${color ? tc[color] : ""}`}>{value ?? "–"}</p>
        </div>
    );
}

function Legend({ color, label }) {
    return (
        <div className="flex items-center gap-1.5">
            <div className="w-2.5 h-2.5 rounded-full" style={{ backgroundColor: color }} />
            <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{label}</span>
        </div>
    );
}

// Convert ISO country code to flag emoji
function countryFlag(code) {
    if (!code || code.length !== 2) return "🌐";
    return code.toUpperCase().replace(/./g, (c) => String.fromCodePoint(0x1f1e6 + c.charCodeAt(0) - 65));
}
