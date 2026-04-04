import React from "react";
import UserLayout from "@/pages/(portals)/user/layout";
import { Head } from "@inertiajs/react";

export default function page() {
    return (
        <UserLayout>
            <Head title="Dashboard" />
            <h1>Dashboard</h1>
        </UserLayout>
    );
}
