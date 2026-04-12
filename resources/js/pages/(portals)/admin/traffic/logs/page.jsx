import React, { useState, useEffect } from "react";
import AdminLayout from "../../layout";
import { Head, Link, router } from "@inertiajs/react";
import {
    Search, ShieldCheck, X, Info, Activity,
    Monitor, Smartphone, Tablet, Terminal, Globe, Timer,
} from "lucide-react";
import Pagination from "@/components/ui/pagination";

export default function TrafficLogsPage({ logs, filters }) {
    const [selectedLog, setSelectedLog] = useState(null);
    const [searchTerm,  setSearchTerm]  = useState(filters.uri || "");

    // Debounced search
    useEffect(() => {
        const timer = setTimeout(() => {
            if (searchTerm !== (filters.uri || "")) {
                handleFilterChange("uri", searchTerm);
            }
        }, 500);
        return () => clearTimeout(timer);
    }, [searchTerm]);

    const handleFilterChange = (key, value) => {
        router.get(
            "/admin/traffic/logs",
            { ...filters, [key]: value, page: 1 },
            { preserveState: true, preserveScroll: true },
        );
    };

    return (
        <AdminLayout>
            <Head title="Traffic Logs" />
            <div className="max-w-7xl mx-auto space-y-5 md:pb-12">

                {/* Header */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Traffic History</h1>
                        <p className="text-sm text-gray-500 mt-0.5">
                            Detailed audit log · {logs.total ?? 0} total records
                        </p>
                    </div>
                    <Link
                        href="/admin/traffic"
                        className="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-100 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all shadow-sm"
                    >
                        <Activity size={16} />
                        Back to Dashboard
                    </Link>
                </div>

                {/* Filter Bar */}
                <div className="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col lg:flex-row gap-3 items-start lg:items-center">
                    <div className="relative flex-1 w-full">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={15} />
                        <input
                            type="text"
                            placeholder="Filter by URI path…"
                            className="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-100 transition-all"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                    </div>

                    <div className="flex items-center gap-2 flex-wrap">
                        <select
                            className="bg-gray-50 border-none rounded-xl text-sm font-semibold text-gray-600 px-3 py-2.5 focus:ring-2 focus:ring-indigo-100 cursor-pointer"
                            value={filters.days || 0}
                            onChange={(e) => handleFilterChange("days", e.target.value)}
                        >
                            <option value="0">All Time</option>
                            <option value="1">Today</option>
                            <option value="7">Last 7 Days</option>
                            <option value="30">Last 30 Days</option>
                            <option value="90">Last 90 Days</option>
                        </select>

                        <select
                            className="bg-gray-50 border-none rounded-xl text-sm font-semibold text-gray-600 px-3 py-2.5 focus:ring-2 focus:ring-indigo-100 cursor-pointer"
                            value={filters.device_type || ""}
                            onChange={(e) => handleFilterChange("device_type", e.target.value)}
                        >
                            <option value="">All Devices</option>
                            <option value="Desktop">Desktop</option>
                            <option value="Mobile">Mobile</option>
                            <option value="Tablet">Tablet</option>
                        </select>

                        <select
                            className="bg-gray-50 border-none rounded-xl text-sm font-semibold text-gray-600 px-3 py-2.5 focus:ring-2 focus:ring-indigo-100 cursor-pointer"
                            value={filters.status_code || ""}
                            onChange={(e) => handleFilterChange("status_code", e.target.value)}
                        >
                            <option value="">All Status</option>
                            <option value="200">200 OK</option>
                            <option value="301">301 Redirect</option>
                            <option value="302">302 Redirect</option>
                            <option value="404">404 Not Found</option>
                            <option value="500">500 Error</option>
                        </select>

                        <button
                            onClick={() => handleFilterChange("is_bot", filters.is_bot ? 0 : 1)}
                            className={`flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-bold transition-all border shrink-0 ${
                                filters.is_bot
                                    ? "bg-violet-50 border-violet-100 text-violet-600"
                                    : "bg-white border-gray-100 text-gray-400 hover:text-gray-600"
                            }`}
                        >
                            <ShieldCheck size={15} />
                            {filters.is_bot ? "Showing Bots" : "Bots Hidden"}
                        </button>
                    </div>
                </div>

                {/* Logs Table */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-gray-50/60 border-b border-gray-100">
                                    {["URI & Method", "Visitor", "Platform", "Status", "Response", "Country", "Time", ""].map((h) => (
                                        <th key={h} className="px-5 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">{h}</th>
                                    ))}
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50 text-sm">
                                {logs.data.map((log) => (
                                    <tr key={log.id} className="group hover:bg-gray-50/40 transition-colors">
                                        <td className="px-5 py-3.5">
                                            <p className="font-bold text-gray-900 truncate max-w-[200px]">{log.uri}</p>
                                            <span className="text-[10px] font-black text-indigo-500 uppercase">{log.method}</span>
                                        </td>
                                        <td className="px-5 py-3.5">
                                            <p className="font-medium text-gray-600 text-xs">{log.ip_address}</p>
                                            <p className="text-[10px] text-gray-400 font-bold uppercase">{log.browser}</p>
                                        </td>
                                        <td className="px-5 py-3.5">
                                            <div className="flex items-center gap-2">
                                                <DeviceIcon type={log.device_type} />
                                                <span className="text-xs text-gray-600 font-medium">{log.os}</span>
                                            </div>
                                        </td>
                                        <td className="px-5 py-3.5">
                                            <StatusBadge code={log.status_code} />
                                        </td>
                                        <td className="px-5 py-3.5">
                                            <span className={`text-xs font-bold ${
                                                log.response_time > 1000 ? "text-red-500" :
                                                log.response_time > 500  ? "text-amber-500" : "text-emerald-600"
                                            }`}>
                                                {log.response_time ? `${log.response_time}ms` : "–"}
                                            </span>
                                        </td>
                                        <td className="px-5 py-3.5">
                                            {log.country_code ? (
                                                <span className="flex items-center gap-1 text-xs text-gray-600">
                                                    <span>{countryFlag(log.country_code)}</span>
                                                    <span className="font-medium">{log.country_code}</span>
                                                </span>
                                            ) : (
                                                <span className="text-gray-300 text-xs">–</span>
                                            )}
                                        </td>
                                        <td className="px-5 py-3.5">
                                            <p className="font-bold text-gray-900 text-xs">
                                                {new Date(log.created_at).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })}
                                            </p>
                                            <p className="text-[10px] text-gray-400 font-semibold">
                                                {new Date(log.created_at).toLocaleDateString()}
                                            </p>
                                        </td>
                                        <td className="px-5 py-3.5 text-right">
                                            <button
                                                onClick={() => setSelectedLog(log)}
                                                className="p-2 text-gray-300 hover:text-indigo-500 hover:bg-indigo-50 rounded-lg transition-all"
                                            >
                                                <Info size={16} />
                                            </button>
                                        </td>
                                    </tr>
                                ))}

                                {logs.data.length === 0 && (
                                    <tr>
                                        <td colSpan={8} className="px-5 py-16 text-center text-sm text-gray-400">
                                            No logs match the current filters.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={logs.links} />
                </div>
            </div>

            {/* Detail Modal */}
            {selectedLog && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md bg-black/20 animate-fadeIn">
                    <div className="bg-white rounded-3xl shadow-2xl border border-gray-100 max-w-lg w-full overflow-hidden animate-slideUp">
                        <div className="p-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-indigo-50 to-white">
                            <div className="flex items-center gap-2">
                                <Terminal size={16} className="text-indigo-500" />
                                <h3 className="font-bold text-gray-900">System Audit Log</h3>
                            </div>
                            <button onClick={() => setSelectedLog(null)} className="p-2 hover:bg-white rounded-xl text-gray-400 hover:text-gray-700 transition-all">
                                <X size={18} />
                            </button>
                        </div>
                        <div className="p-6 space-y-5 overflow-y-auto max-h-[80vh]">
                            <div className="grid grid-cols-2 gap-x-8 gap-y-5 text-sm">
                                <DI label="URI PATH"     value={selectedLog.uri}           highlight />
                                <DI label="METHOD"       value={selectedLog.method}         color="blue" />
                                <DI label="STATUS CODE"  value={selectedLog.status_code} color={selectedLog.status_code >= 400 ? "red" : "green"} />
                                <DI label="RESPONSE"     value={selectedLog.response_time ? `${selectedLog.response_time}ms` : "–"} />
                                <DI label="VISIT TIME"   value={new Date(selectedLog.created_at).toLocaleString()} />
                                <DI label="IP ADDRESS"   value={selectedLog.ip_address} />
                                <DI label="COUNTRY"      value={selectedLog.country_name || "Unknown"} />
                                <DI label="PLATFORM"     value={`${selectedLog.device_type} · ${selectedLog.os}`} />
                                <DI label="BROWSER"      value={selectedLog.browser} />
                                <DI label="BOT STATUS"   value={selectedLog.is_bot ? "Identified Bot" : "Human User"} color={selectedLog.is_bot ? "red" : "green"} />
                                <DI label="NEW VISITOR"  value={selectedLog.is_new_visitor ? "Yes — First Visit" : "Returning"} />
                                <DI label="REFERRER"     value={selectedLog.referrer || "Direct / None"} />
                            </div>
                            <div className="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <Terminal size={11} /> Raw User Agent
                                </p>
                                <p className="text-xs text-gray-500 font-mono break-all leading-relaxed bg-white p-3 rounded-xl border border-gray-100">
                                    {selectedLog.user_agent}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}

function DeviceIcon({ type }) {
    if (type === "Mobile") return <Smartphone size={13} className="text-amber-500" />;
    if (type === "Tablet") return <Tablet     size={13} className="text-sky-500"   />;
    return                        <Monitor    size={13} className="text-gray-400"  />;
}

function StatusBadge({ code }) {
    if (!code) return <span className="text-gray-300 text-xs">–</span>;
    const c =
        code < 300  ? "bg-emerald-50 text-emerald-700 border-emerald-100" :
        code < 400  ? "bg-amber-50 text-amber-700 border-amber-100" :
        code < 500  ? "bg-red-50 text-red-700 border-red-100" :
                      "bg-rose-50 text-rose-800 border-rose-100";
    return <span className={`px-2 py-0.5 rounded-md text-[10px] font-black border ${c}`}>{code}</span>;
}

function DI({ label, value, highlight, color }) {
    const tc = { green: "text-emerald-600", red: "text-red-600", blue: "text-indigo-600" };
    return (
        <div className="space-y-1">
            <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{label}</p>
            <p className={`font-bold break-words leading-tight ${highlight ? "text-indigo-600" : "text-gray-900"} ${color ? tc[color] : ""}`}>
                {value ?? "–"}
            </p>
        </div>
    );
}

function countryFlag(code) {
    if (!code || code.length !== 2) return "🌐";
    return code.toUpperCase().replace(/./g, (c) => String.fromCodePoint(0x1f1e6 + c.charCodeAt(0) - 65));
}
