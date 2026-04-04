import React from "react";
import UserLayout from "@/pages/(portals)/user/layout";
import { Head, Link } from "@inertiajs/react";
import { User, Clock, Activity, ShieldCheck, ArrowRight } from "lucide-react";

export default function UserDashboardPage({ user, stats, recent_activity }) {
    return (
        <UserLayout>
            <Head title="User Dashboard" />
            <div className="max-w-7xl mx-auto space-y-8 md:pb-12">
                
                {/* Welcome Hero */}
                <div className="bg-gradient-to-r from-primary to-blue-600 rounded-3xl p-8 md:p-10 text-white shadow-lg overflow-hidden relative">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
                    <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h1 className="text-3xl font-bold mb-2">Welcome back, {user.name.split(' ')[0]}!</h1>
                            <p className="text-primary-foreground/80 max-w-lg text-sm">
                                Check out your personal activity tracker and complete your profile to unlock all platform features.
                            </p>
                        </div>
                        <div className="shrink-0 flex items-center gap-4 bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                            <div className="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center border-2 border-white overflow-hidden">
                                {user.profile_image ? (
                                    <img src={'/storage/' + user.profile_image} className="w-full h-full object-cover" />
                                ) : (
                                    <span className="text-2xl font-bold">{user.name.charAt(0)}</span>
                                )}
                            </div>
                            <div>
                                <p className="font-semibold">{user.email}</p>
                                <span className="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full bg-white/20 font-medium tracking-wide mt-1">
                                    <ShieldCheck size={12} />
                                    Standard User
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {/* Member Since Card */}
                    <div className="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 relative overflow-hidden transition-all hover:shadow-md">
                        <div className="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div className="relative z-10">
                            <div className="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 border border-blue-100">
                                <Clock size={24} />
                            </div>
                            <h3 className="text-3xl font-bold text-gray-900 mb-1">{stats.member_since}</h3>
                            <p className="text-sm font-medium text-gray-500">Member Since</p>
                        </div>
                    </div>

                    {/* Total Actions Card */}
                    <div className="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 relative overflow-hidden transition-all hover:shadow-md">
                        <div className="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div className="relative z-10">
                            <div className="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center mb-4 border border-purple-100">
                                <Activity size={24} />
                            </div>
                            <h3 className="text-3xl font-bold text-gray-900 mb-1">{stats.total_actions}</h3>
                            <p className="text-sm font-medium text-gray-500">Total Interactions</p>
                        </div>
                    </div>

                    {/* Profile Completion Card */}
                    <div className="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 transition-all hover:shadow-md flex flex-col justify-between">
                        <div>
                            <div className="flex justify-between items-center mb-2">
                                <div className="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center border border-amber-100">
                                    <User size={24} />
                                </div>
                                <span className="font-bold text-2xl text-gray-900">{stats.profile_completion}%</span>
                            </div>
                            <h3 className="text-sm font-bold text-gray-900 mb-1">Profile Completion</h3>
                            <p className="text-xs text-gray-500 mb-4">Complete your account details</p>
                        </div>
                        <div>
                            <div className="w-full h-2 bg-gray-100 rounded-full overflow-hidden mb-3">
                                <div 
                                    className="h-full bg-amber-500 transition-all duration-1000 ease-out" 
                                    style={{ width: `${stats.profile_completion}%` }}
                                ></div>
                            </div>
                            {stats.profile_completion < 100 && (
                                <Link href="/user/profile" className="text-xs font-bold text-primary flex items-center gap-1 hover:text-primary/80 transition-colors">
                                    Complete Profile <ArrowRight size={14} />
                                </Link>
                            )}
                        </div>
                    </div>
                </div>

                {/* Personal Activity Feed */}
                <div className="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h3 className="font-bold text-gray-900 text-lg">Your Recent Activity</h3>
                            <p className="text-gray-500 text-sm mt-1">A timeline of your latest account events.</p>
                        </div>
                        <Link href="/user/activity-logs" className="px-4 py-2 border border-gray-200 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                            View All Logs
                        </Link>
                    </div>

                    {recent_activity.length > 0 ? (
                        <div className="space-y-6">
                            {recent_activity.map((log) => (
                                <div key={log.id} className="flex gap-4 relative">
                                    <div className="absolute top-8 bottom-[-24px] left-5 w-px bg-gray-100 last:hidden"></div>
                                    
                                    <div className="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0 z-10 text-blue-600">
                                        <Activity size={18} />
                                    </div>
                                    <div className="flex-1 bg-gray-50 rounded-xl p-4 border border-gray-100/50">
                                        <div className="flex items-start justify-between gap-4">
                                            <div>
                                                <p className="text-gray-900 font-medium">{log.description}</p>
                                            </div>
                                            <div className="flex items-center gap-1.5 text-xs text-gray-400 whitespace-nowrap shrink-0 mt-1">
                                                <Clock size={12} />
                                                {new Date(log.created_at).toLocaleDateString()}
                                            </div>
                                        </div>
                                        <div className="mt-3 flex gap-2">
                                            <span className="px-2.5 py-1 bg-white border border-gray-200 text-gray-600 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                {log.subject_type || 'SYSTEM'}
                                            </span>
                                            <span className="px-2.5 py-1 bg-blue-100/50 border border-blue-200 text-blue-700 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                {log.action}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-12 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                            No recent activities recorded on your account yet.
                        </div>
                    )}
                </div>
            </div>
        </UserLayout>
    );
}
