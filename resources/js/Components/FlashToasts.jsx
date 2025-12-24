import React from 'react';
import {
    useFlashSuccess,
    useFlashError,
    useFlashWarning,
    useFlashInfo,
    useFlashMessage,
} from '@/hooks/useSharedProps';

const variants = {
    success: 'bg-green-600 text-white shadow-lg shadow-green-500/30',
    error: 'bg-red-600 text-white shadow-lg shadow-red-500/30',
    warning: 'bg-amber-500 text-white shadow-lg shadow-amber-400/30',
    info: 'bg-sky-600 text-white shadow-lg shadow-sky-500/30',
    message: 'bg-slate-700 text-white shadow-lg shadow-slate-500/30',
};

export default function FlashToasts() {
    const success = useFlashSuccess();
    const error = useFlashError();
    const warning = useFlashWarning();
    const info = useFlashInfo();
    const message = useFlashMessage();

    const items = React.useMemo(() => {
        const entries = [
            { key: 'success', value: success },
            { key: 'error', value: error },
            { key: 'warning', value: warning },
            { key: 'info', value: info },
            { key: 'message', value: message },
        ];
        return entries.filter((item) => item.value);
    }, [success, error, warning, info, message]);

    if (!items.length) return null;

    return (
        <div className="fixed top-4 right-4 z-50 flex flex-col gap-3">
            {items.map(({ key, value }) => (
                <div
                    key={`${key}-${value}`}
                    className={`min-w-[240px] max-w-sm rounded-lg px-4 py-3 text-sm font-medium ${variants[key] || variants.message}`}
                    role="status"
                >
                    {value}
                </div>
            ))}
        </div>
    );
}
