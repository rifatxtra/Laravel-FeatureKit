import React from 'react';
import FlashToasts from '@/Components/FlashToasts';

export default function AppLayout({ children }) {
    return (
        <>
            <FlashToasts />
            {children}
        </>
    );
}
