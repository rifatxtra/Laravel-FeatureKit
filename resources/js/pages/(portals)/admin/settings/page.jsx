import React, { useState } from "react";
import AdminLayout from "@/pages/(portals)/admin/layout";
import { Head, useForm } from "@inertiajs/react";
import { Settings, Save, Upload, Shield, Monitor } from "lucide-react";
import { TOAST_TYPES } from "@/Utils/toast";

export default function SettingsPage({ settings }) {
    // General Settings Form
    const {
        data: generalData,
        setData: setGeneralData,
        post: postGeneral,
        processing: processingGeneral,
        errors: generalErrors,
    } = useForm({
        app_name: settings?.app_name || "",
        is_maintenance: settings?.is_maintenance === "1",
        maintenance_duration: settings?.maintenance_duration || "15 mins",
        email_support: settings?.email_support || "",
    });

    // Logo Form
    const {
        data: logoData,
        setData: setLogoData,
        post: postLogo,
        processing: processingLogo,
    } = useForm({
        logo: null,
    });

    // Favicon Form
    const {
        data: faviconData,
        setData: setFaviconData,
        post: postFavicon,
        processing: processingFavicon,
    } = useForm({
        favicon: null,
    });

    const submitGeneral = (e) => {
        e.preventDefault();
        postGeneral("/admin/settings", {
            preserveScroll: true,
        });
    };

    const submitLogo = (e) => {
        e.preventDefault();
        postLogo("/admin/settings/logo", {
            preserveScroll: true,
        });
    };

    const submitFavicon = (e) => {
        e.preventDefault();
        postFavicon("/admin/settings/favicon", {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout>
            <Head title="System Settings" />
            <div className="max-w-4xl mx-auto space-y-8 md:pb-20">
                <div>
                    <h1 className="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">
                        System Configuration
                    </h1>
                    <p className="text-sm text-gray-500 mt-1">
                        Manage global platform Branding and Maintenance mode.
                    </p>
                </div>

                {/* General Settings */}
                <section className="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
                    <div className="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                        <Monitor size={18} className="text-primary" />
                        <h2 className="font-semibold text-gray-900">General Appearance</h2>
                    </div>
                    <form onSubmit={submitGeneral} className="p-6 space-y-6">
                        <div className="grid gap-6 md:grid-cols-2">
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">App Name</label>
                                <input
                                    type="text"
                                    value={generalData.app_name}
                                    onChange={(e) => setGeneralData("app_name", e.target.value)}
                                    className="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                    placeholder="Enter Site Name"
                                />
                                {generalErrors.app_name && <p className="text-xs text-red-500">{generalErrors.app_name}</p>}
                            </div>
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">Support Email</label>
                                <input
                                    type="email"
                                    value={generalData.email_support}
                                    onChange={(e) => setGeneralData("email_support", e.target.value)}
                                    className="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                    placeholder="support@example.com"
                                />
                                {generalErrors.email_support && <p className="text-xs text-red-500">{generalErrors.email_support}</p>}
                            </div>
                        </div>

                        {/* Maintenance Mode Toggle */}
                        <div className="p-4 bg-amber-50 rounded-xl border border-amber-100 flex items-center justify-between">
                            <div className="flex items-center gap-3">
                                <Shield className="text-amber-600" size={24} />
                                <div className="flex-1">
                                    <h3 className="text-sm font-bold text-amber-900">Maintenance Mode</h3>
                                    <p className="text-xs text-amber-700">Redirects users to the maintenance page. Admins still have access.</p>
                                    <input 
                                        type="text" 
                                        value={generalData.maintenance_duration}
                                        onChange={(e) => setGeneralData("maintenance_duration", e.target.value)}
                                        className="mt-2 text-xs px-4 py-1.5 bg-white border border-amber-200 rounded-xl outline-none focus:border-amber-400 w-full"
                                        placeholder="Estimate (e.g. 15 mins)"
                                    />
                                </div>
                            </div>
                            <button
                                type="button"
                                onClick={() => setGeneralData("is_maintenance", !generalData.is_maintenance)}
                                className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none ${generalData.is_maintenance ? "bg-amber-600" : "bg-gray-300"}`}
                            >
                                <span className={`inline-block h-4 w-4 transform rounded-full bg-white transition-transform ${generalData.is_maintenance ? "translate-x-6" : "translate-x-1"}`} />
                            </button>
                        </div>

                        <div className="flex justify-end pt-4">
                            <button
                                type="submit"
                                disabled={processingGeneral}
                                className="flex items-center px-6 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary-hover shadow-lg shadow-primary/20 transition-all disabled:opacity-50"
                            >
                                <Save size={18} className="mr-2" />
                                {processingGeneral ? "Saving..." : "Save Changes"}
                            </button>
                        </div>
                    </form>
                </section>

                <div className="grid md:grid-cols-2 gap-8">
                    {/* Logo Section */}
                    <section className="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden flex flex-col">
                        <div className="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                            <Upload size={18} className="text-primary" />
                            <h2 className="font-semibold text-gray-900">App Logo</h2>
                        </div>
                        <div className="p-6 flex-1 flex flex-col items-center justify-center space-y-6">
                            <div className="w-32 h-32 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center p-4">
                                <img
                                    src={settings?.app_logo || "/logo.png"}
                                    alt="Current Logo"
                                    className="max-w-full max-h-full object-contain"
                                    id="logo-preview"
                                />
                            </div>
                            <div className="text-center">
                                <label className="cursor-pointer bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-medium hover:bg-gray-50 transition-colors inline-block">
                                    Change Logo
                                    <input
                                        type="file"
                                        className="hidden"
                                        accept="image/*"
                                        onChange={(e) => {
                                            const file = e.target.files[0];
                                            if (file) {
                                                setLogoData("logo", file);
                                                const reader = new FileReader();
                                                reader.onload = (e) => (document.getElementById("logo-preview").src = e.target.result);
                                                reader.readAsDataURL(file);
                                            }
                                        }}
                                    />
                                </label>
                                <p className="text-[10px] text-gray-400 mt-2 uppercase tracking-tight">Recommended: PNG/SVG transparent</p>
                            </div>
                            {logoData.logo && (
                                <button
                                    onClick={submitLogo}
                                    disabled={processingLogo}
                                    className="w-full py-2 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition-all disabled:opacity-50"
                                >
                                    {processingLogo ? "Uploading..." : "Upload Logo"}
                                </button>
                            )}
                        </div>
                    </section>

                    {/* Favicon Section */}
                    <section className="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden flex flex-col">
                        <div className="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-2">
                            <Shield size={18} className="text-primary" />
                            <h2 className="font-semibold text-gray-900">Dynamic Favicon</h2>
                        </div>
                        <div className="p-6 flex-1 flex flex-col items-center justify-center space-y-6">
                            <div className="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl flex items-center justify-center">
                                <img
                                    src={settings?.app_favicon || "/favicon.ico"}
                                    alt="Current Favicon"
                                    className="w-10 h-10 object-contain"
                                    id="favicon-preview"
                                />
                            </div>
                            <div className="text-center">
                                <label className="cursor-pointer bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-medium hover:bg-gray-50 transition-colors inline-block">
                                    Change Favicon
                                    <input
                                        type="file"
                                        className="hidden"
                                        accept="image/*"
                                        onChange={(e) => {
                                            const file = e.target.files[0];
                                            if (file) {
                                                setFaviconData("favicon", file);
                                                const reader = new FileReader();
                                                reader.onload = (e) => (document.getElementById("favicon-preview").src = e.target.result);
                                                reader.readAsDataURL(file);
                                            }
                                        }}
                                    />
                                </label>
                                <p className="text-[10px] text-gray-400 mt-2 uppercase tracking-tight">Will be converted to standard .ico</p>
                            </div>
                            {faviconData.favicon && (
                                <button
                                    onClick={submitFavicon}
                                    disabled={processingFavicon}
                                    className="w-full py-2 bg-gray-900 text-white rounded-xl font-bold hover:bg-black transition-all disabled:opacity-50"
                                >
                                    {processingFavicon ? "Update Favicon" : "Update Favicon"}
                                </button>
                            )}
                        </div>
                    </section>
                </div>
            </div>
        </AdminLayout>
    );
}
