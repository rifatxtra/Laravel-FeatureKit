import React from "react";
import AdminLayout from "../layout";
import { Head } from "@inertiajs/react";

export default function page() {
    return (
        <AdminLayout>
            <Head title="Dashboard" />
            <h1>Dashboard</h1>
        </AdminLayout>
    );
}
