import { Head, Link, useForm } from '@inertiajs/react';
import { useFlash, useError } from '@/hooks/useSharedProps';

export default function Login() {
    const flash = useFlash();
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <>
            <Head title="Login" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center px-4">
                <div className="max-w-md w-full">
                    {/* Header */}
                    <div className="text-center mb-8">
                        <Link href="/" className="text-2xl font-bold text-gray-900">
                            Laravel FeatureKit
                        </Link>
                        <h1 className="mt-6 text-3xl font-bold text-gray-900">Welcome back</h1>
                        <p className="mt-2 text-gray-600">Sign in to your account</p>
                    </div>

                    {/* Flash Messages */}
                    {flash.success && (
                        <div className="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                            {flash.success}
                        </div>
                    )}
                    {flash.error && (
                        <div className="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            {flash.error}
                        </div>
                    )}

                    {/* Login Form */}
                    <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                        <form onSubmit={submit} className="space-y-6">
                            {/* Email */}
                            <div>
                                <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition ${
                                        errors.email ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="you@example.com"
                                    autoFocus
                                />
                                {errors.email && (
                                    <p className="mt-1 text-sm text-red-600">{errors.email}</p>
                                )}
                            </div>

                            {/* Password */}
                            <div>
                                <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-2">
                                    Password
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition ${
                                        errors.password ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="••••••••"
                                />
                                {errors.password && (
                                    <p className="mt-1 text-sm text-red-600">{errors.password}</p>
                                )}
                            </div>

                            {/* Remember Me */}
                            <div className="flex items-center justify-between">
                                <label className="flex items-center">
                                    <input
                                        type="checkbox"
                                        checked={data.remember}
                                        onChange={(e) => setData('remember', e.target.checked)}
                                        className="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    />
                                    <span className="ml-2 text-sm text-gray-600">Remember me</span>
                                </label>
                                
                                <Link href="/forgot-password" className="text-sm text-blue-600 hover:text-blue-700">
                                    Forgot password?
                                </Link>
                            </div>

                            {/* Submit Button */}
                            <button
                                type="submit"
                                disabled={processing}
                                aria-busy={processing}
                                className="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Signing in...' : 'Sign in'}
                            </button>
                        </form>

                        {/* Register Link */}
                        <div className="mt-6 text-center text-sm text-gray-600">
                            Don't have an account?{' '}
                            <Link href="/register" className="text-blue-600 hover:text-blue-700 font-semibold">
                                Create one now
                            </Link>
                        </div>
                    </div>

                    {/* Back to Home */}
                    <div className="mt-6 text-center">
                        <Link href="/" className="text-sm text-gray-600 hover:text-gray-900">
                            ← Back to home
                        </Link>
                    </div>
                </div>
            </div>
        </>
    );
}
