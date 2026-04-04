import React from "react";
import AdminLayout from "@/pages/(portals)/admin/layout";
import { Head } from "@inertiajs/react";
import { Settings } from "lucide-react";

export default function SettingsPage() {
    return (
        <AdminLayout>
            <Head title="System Settings" />
            <div className="max-w-7xl mx-auto space-y-6 md:pb-12">
                <div>
                    <h1 className="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">
                        System Configuration
                    </h1>
                    <p className="text-sm text-gray-500 mt-1">
                        Manage global platform settings and integrations.
                    </p>
                </div>

                <div className="bg-white border border-gray-200 shadow-sm rounded-2xl p-12 text-center flex flex-col items-center justify-center">
                    <div className="w-16 h-16 bg-primary/5 rounded-full flex items-center justify-center mb-4 border border-primary/10">
                        <Settings size={32} className="text-primary opacity-50" />
                    </div>
                    <h2 className="text-xl font-bold text-gray-900">Settings Coming Soon</h2>
                    <p className="text-gray-500 mt-2 max-w-md">
                        This section has been scaffolded and is fully wired up. You can start building your configuration forms here.
                    </p>
                </div>
            </div>
        </AdminLayout>
    );
}
