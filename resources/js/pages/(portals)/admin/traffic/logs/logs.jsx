import React, { useState, useEffect } from "react";
import AdminLayout from "../../layout";
import { Head, Link, router } from "@inertiajs/react";
import {
    Search,
    ShieldCheck,
    X,
    Info,
    ChevronLeft,
    ChevronRight,
    Activity,
    Clock,
    Globe,
    Monitor,
    Smartphone,
    Tablet,
    Terminal,
} from "lucide-react";
import Pagination from "@/components/ui/pagination";

export default function TrafficLogsPage({ logs, filters }) {
    const [selectedLog, setSelectedLog] = useState(null);
    const [searchTerm, setSearchTerm] = useState(filters.uri || "");

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
            {
                ...filters,
                [key]: value,
                page: 1, // Reset to page 1 on filter change
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    return (
        <AdminLayout>
            <Head title="Traffic Logs" />
            <div className="max-w-7xl mx-auto space-y-6 md:pb-12">
                {/* Header Section */}
                <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">
                            Traffic History
                        </h1>
                        <p className="text-sm text-gray-500">
                            Detailed audit log of all public visitor
                            interactions.
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
                <div className="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col lg:flex-row gap-4 items-center">
                    <div className="relative flex-1 w-full">
                        <Search
                            className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                            size={16}
                        />
                        <input
                            type="text"
                            placeholder="Filter by URI path..."
                            className="w-full pl-10 pr-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-100 transition-all"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                    </div>

                    <div className="flex items-center gap-3 w-full lg:w-auto overflow-x-auto pb-2 lg:pb-0">
                        <select
                            className="bg-gray-50 border-none rounded-xl text-sm font-bold text-gray-600 px-4 py-2.5 focus:ring-2 focus:ring-blue-100 cursor-pointer"
                            value={filters.days || 0}
                            onChange={(e) =>
                                handleFilterChange("days", e.target.value)
                            }
                        >
                            <option value="0">All Time</option>
                            <option value="1">Today</option>
                            <option value="7">Last 7 Days</option>
                            <option value="30">Last 30 Days</option>
                            <option value="90">Last 90 Days</option>
                        </select>

                        <button
                            onClick={() =>
                                handleFilterChange(
                                    "is_bot",
                                    filters.is_bot ? 0 : 1,
                                )
                            }
                            className={`flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition-all border shrink-0 ${
                                filters.is_bot
                                    ? "bg-purple-50 border-purple-100 text-purple-600"
                                    : "bg-white border-gray-100 text-gray-400"
                            }`}
                        >
                            <ShieldCheck size={16} />
                            {filters.is_bot ? "Showing Bots" : "Excluding Bots"}
                        </button>
                    </div>
                </div>

                {/* Logs Table */}
                <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-gray-50/50 border-b border-gray-100">
                                    <th className="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        URI & Method
                                    </th>
                                    <th className="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Visitor Info
                                    </th>
                                    <th className="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Platform
                                    </th>
                                    <th className="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        Time
                                    </th>
                                    <th className="px-6 py-4 text-right"></th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-50 text-sm">
                                {logs.data.map((log) => (
                                    <tr
                                        key={log.id}
                                        className="group hover:bg-gray-50/30 transition-colors"
                                    >
                                        <td className="px-6 py-4">
                                            <div className="flex flex-col">
                                                <span className="font-bold text-gray-900 truncate max-w-[250px]">
                                                    {log.uri}
                                                </span>
                                                <span className="text-[10px] font-black text-blue-500 uppercase">
                                                    {log.method}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex flex-col">
                                                <span className="font-medium text-gray-600">
                                                    {log.ip_address}
                                                </span>
                                                <span className="text-[10px] text-gray-400 font-bold uppercase tracking-tight truncate max-w-[150px]">
                                                    {log.browser}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2">
                                                <DeviceIcon
                                                    type={log.device_type}
                                                />
                                                <span className="text-gray-600 font-medium">
                                                    {log.os}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex flex-col">
                                                <span className="font-bold text-gray-900">
                                                    {new Date(
                                                        log.created_at,
                                                    ).toLocaleTimeString([], {
                                                        hour: "2-digit",
                                                        minute: "2-digit",
                                                    })}
                                                </span>
                                                <span className="text-[10px] text-gray-400 font-bold uppercase tracking-tight">
                                                    {new Date(
                                                        log.created_at,
                                                    ).toLocaleDateString()}
                                                </span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <button
                                                onClick={() =>
                                                    setSelectedLog(log)
                                                }
                                                className="p-2 text-gray-300 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-all"
                                            >
                                                <Info size={18} />
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={logs.links} />
                </div>
            </div>

            {/* Same Detail Modal from dashboard */}
            {selectedLog && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md bg-black/10 animate-fadeIn">
                    <div className="bg-white rounded-3xl shadow-2xl border border-gray-100 max-w-lg w-full overflow-hidden animate-slideUp">
                        <div className="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                            <h3 className="font-bold text-gray-900 text-lg tracking-tight">
                                System Audit Log
                            </h3>
                            <button
                                onClick={() => setSelectedLog(null)}
                                className="p-2 hover:bg-white rounded-xl text-gray-400 hover:text-gray-900 transition-all"
                            >
                                <X size={20} />
                            </button>
                        </div>
                        <div className="p-8 space-y-6 overflow-y-auto max-h-[80vh]">
                            <div className="grid grid-cols-2 gap-x-8 gap-y-6 text-sm">
                                <DetailItem
                                    label="URI PATH"
                                    value={selectedLog.uri}
                                    highlight
                                />
                                <DetailItem
                                    label="METHOD"
                                    value={selectedLog.method}
                                    color="blue"
                                />
                                <DetailItem
                                    label="VISIT TIME"
                                    value={new Date(
                                        selectedLog.created_at,
                                    ).toLocaleString()}
                                />
                                <DetailItem
                                    label="IP ADDRESS"
                                    value={selectedLog.ip_address}
                                />
                                <DetailItem
                                    label="PLATFORM"
                                    value={`${selectedLog.device_type} • ${selectedLog.os}`}
                                />
                                <DetailItem
                                    label="BROWSER"
                                    value={selectedLog.browser}
                                />
                                <DetailItem
                                    label="BOT STATUS"
                                    value={
                                        selectedLog.is_bot
                                            ? "Identified Bot"
                                            : "Human User"
                                    }
                                    color={
                                        selectedLog.is_bot ? "purple" : "green"
                                    }
                                />
                                <DetailItem
                                    label="REFERRER"
                                    value={
                                        selectedLog.referrer ||
                                        "Direct / Internal"
                                    }
                                />
                            </div>
                            <div className="bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <Terminal size={12} />
                                    Raw User Agent String
                                </p>
                                <p className="text-xs text-gray-500 font-medium leading-relaxed font-mono break-all bg-white p-3 rounded-lg border border-gray-50 shadow-inner">
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
    if (type === "Mobile")
        return <Smartphone size={14} className="text-amber-500" />;
    if (type === "Tablet")
        return <Tablet size={14} className="text-blue-500" />;
    return <Monitor size={14} className="text-gray-400" />;
}

function DetailItem({ label, value, highlight, color }) {
    const textColors = {
        green: "text-green-600",
        purple: "text-purple-600",
        blue: "text-blue-600",
    };
    return (
        <div className="space-y-1">
            <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                {label}
            </p>
            <p
                className={`font-bold tracking-tight ${highlight ? "text-blue-600" : "text-gray-900"} ${color ? textColors[color] : ""} break-words leading-tight`}
            >
                {value}
            </p>
        </div>
    );
}
