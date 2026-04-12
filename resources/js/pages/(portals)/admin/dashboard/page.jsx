import React, { useState } from "react";
import AdminLayout from "../layout";
import { Head, Link } from "@inertiajs/react";
import { Users, UsersRound, UserMinus, Activity, Clock } from "lucide-react";

export default function DashboardPage({ metrics, recent_activity }) {
    return (
        <AdminLayout>
            <Head title="Admin Dashboard" />
            <div className="max-w-7xl mx-auto space-y-8 md:pb-12">
                <div>
                    <h1 className="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">
                        Admin Dashboard
                    </h1>
                    <p className="text-sm text-gray-500 mt-1">
                        System overview, user metrics, and real-time activity
                        tracking.
                    </p>
                </div>

                {/* Metrics Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <MetricCard
                        title="Total Users"
                        value={metrics.total_users}
                        icon={<Users size={24} className="text-blue-600" />}
                        bg="bg-blue-50"
                        border="border-blue-100"
                    />
                    <MetricCard
                        title="Active Users"
                        value={metrics.active_users}
                        icon={
                            <UsersRound size={24} className="text-green-600" />
                        }
                        bg="bg-green-50"
                        border="border-green-100"
                    />
                    <MetricCard
                        title="Suspended Users"
                        value={metrics.suspended_users}
                        icon={<UserMinus size={24} className="text-red-600" />}
                        bg="bg-red-50"
                        border="border-red-100"
                    />
                    <MetricCard
                        title="Total Activities"
                        value={metrics.total_activities}
                        icon={
                            <Activity size={24} className="text-purple-600" />
                        }
                        bg="bg-purple-50"
                        border="border-purple-100"
                    />
                </div>

                {/* Activity Feed and Secondary Layout */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div className="lg:col-span-2 space-y-4">
                        <div className="bg-white border text-sm border-gray-200 shadow-sm rounded-2xl p-6">
                            <div className="flex items-center justify-between mb-6">
                                <div>
                                    <h3 className="font-bold text-gray-900 text-lg">
                                        Recent System Activity
                                    </h3>
                                    <p className="text-gray-500 text-xs mt-1">
                                        Latest actions performed across the
                                        platform
                                    </p>
                                </div>
                                <Activity className="text-gray-300" size={24} />
                            </div>

                            {recent_activity.length > 0 ? (
                                <div className="space-y-6">
                                    {recent_activity.map((log) => (
                                        <div
                                            key={log.id}
                                            className="flex gap-4 relative"
                                        >
                                            {/* Timeline Line */}
                                            <div className="absolute top-8 bottom-[-24px] left-5 w-px bg-gray-100 last:hidden"></div>

                                            <UserAvatar user={log.user} size="md" />
                                            <div className="flex-1 bg-gray-50 rounded-xl p-4 border border-gray-100/50">
                                                <div className="flex items-start justify-between gap-4">
                                                    <div>
                                                        <p className="font-semibold text-gray-900">
                                                            {log.user?.name ||
                                                                "System"}{" "}
                                                            <span className="text-gray-400 font-normal">
                                                                performed an
                                                                action
                                                            </span>
                                                        </p>
                                                        <p className="text-gray-600 mt-1">
                                                            {log.description}
                                                        </p>
                                                    </div>
                                                    <div className="flex items-center gap-1.5 text-xs text-gray-400 whitespace-nowrap shrink-0">
                                                        <Clock size={12} />
                                                        {new Date(
                                                            log.created_at,
                                                        ).toLocaleDateString()}
                                                    </div>
                                                </div>
                                                <div className="mt-3 flex gap-2">
                                                    <span className="px-2.5 py-1 bg-white border border-gray-200 text-gray-600 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                        {log.subject_type ||
                                                            "SYSTEM"}
                                                    </span>
                                                    <span className="px-2.5 py-1 bg-primary/5 border border-primary/10 text-primary rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                        {log.action}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-12 text-gray-500">
                                    No recent activities recorded.
                                </div>
                            )}
                        </div>
                    </div>

                    <div className="space-y-6">
                        {/* Quick Links / Summary widget */}
                        <div className="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                            <h3 className="font-bold text-gray-900 mb-4">
                                Quick Actions
                            </h3>
                            <div className="space-y-2">
                                <Link
                                    href="/admin/users"
                                    className="block p-3 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition"
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center gap-3">
                                            <div className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                                <Users size={16} />
                                            </div>
                                            <span className="font-semibold text-sm text-gray-700">
                                                Manage Users
                                            </span>
                                        </div>
                                    </div>
                                </Link>
                                <Link
                                    href="/admin/cache"
                                    className="block p-3 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition"
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex items-center gap-3">
                                            <div className="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                                <Activity size={16} />
                                            </div>
                                            <span className="font-semibold text-sm text-gray-700">
                                                Clear Cache
                                            </span>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}

function MetricCard({ title, value, icon, bg, border }) {
    return (
        <div
            className={`p-6 rounded-2xl border ${border} ${bg} transition-all hover:shadow-md`}
        >
            <div className="flex justify-between items-start mb-4">
                <div className={`p-3 rounded-xl bg-white/60 shadow-sm`}>
                    {icon}
                </div>
            </div>
            <div>
                <h3 className="text-3xl font-bold text-gray-900 mb-1">
                    {value}
                </h3>
                <p className="text-sm font-medium text-gray-600">{title}</p>
            </div>
        </div>
    );
}

/**
 * UserAvatar — robust profile image with automatic fallback.
 *
 * Handles: null, empty string, and broken image URLs.
 * Falls back to a colored initial-letter avatar.
 *
 * @param {object} user   - User object with `name` and `profile_image`
 * @param {'sm'|'md'|'lg'} size
 */
function UserAvatar({ user, size = "md" }) {
    const [imgError, setImgError] = useState(false);

    const sizes = {
        sm: "w-7 h-7 text-xs",
        md: "w-10 h-10 text-sm",
        lg: "w-14 h-14 text-base",
    };

    // Derive the avatar letter from the user's name, or "?" as ultimate fallback
    const initial = user?.name?.trim()?.[0]?.toUpperCase() ?? "?";

    // Pick a deterministic background colour from the name so each user always
    // gets the same colour — looks more intentional than a random one.
    const COLOURS = [
        "bg-indigo-500", "bg-emerald-500", "bg-amber-500",
        "bg-rose-500",  "bg-sky-500",     "bg-violet-500",
        "bg-teal-500",  "bg-orange-500",
    ];
    const colourIndex = (user?.name?.charCodeAt(0) ?? 0) % COLOURS.length;
    const bgColour    = COLOURS[colourIndex];

    // Consider the image valid only when profile_image is a non-empty string
    // AND the <img> hasn't reported an error yet.
    const hasValidImage =
        typeof user?.profile_image === "string" &&
        user.profile_image.trim() !== "" &&
        !imgError;

    const imageSrc = hasValidImage
        ? "/storage/" + user.profile_image
        : null;

    return (
        <div
            className={`${sizes[size]} rounded-full shrink-0 z-10 overflow-hidden flex items-center justify-center border border-white/20 shadow-sm`}
        >
            {imageSrc ? (
                <img
                    src={imageSrc}
                    alt={user?.name ?? "User avatar"}
                    className="w-full h-full object-cover"
                    onError={() => setImgError(true)}
                />
            ) : (
                <div
                    className={`w-full h-full flex items-center justify-center ${bgColour}`}
                >
                    <span className={`font-bold text-white leading-none`}>
                        {initial}
                    </span>
                </div>
            )}
        </div>
    );
}
