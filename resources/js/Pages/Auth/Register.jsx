import { Head, Link, useForm } from '@inertiajs/react';
import { useFlash } from '@/hooks/useSharedProps';

export default function Register() {
    const flash = useFlash();
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/register');
    };

    return (
        <>
            <Head title="Register" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center px-4 py-12">
                <div className="max-w-md w-full">
                    {/* Header */}
                    <div className="text-center mb-8">
                        <Link href="/" className="text-2xl font-bold text-gray-900">
                            Laravel FeatureKit
                        </Link>
                        <h1 className="mt-6 text-3xl font-bold text-gray-900">Create an account</h1>
                        <p className="mt-2 text-gray-600">Get started with your free account</p>
                    </div>

                    {/* Flash Messages */}
                    {flash.error && (
                        <div className="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                            {flash.error}
                        </div>
                    )}

                    {/* Register Form */}
                    <div className="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                        <form onSubmit={submit} className="space-y-5">
                            {/* Name */}
                            <div>
                                <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name
                                </label>
                                <input
                                    id="name"
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition ${
                                        errors.name ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="John Doe"
                                    autoFocus
                                />
                                {errors.name && (
                                    <p className="mt-1 text-sm text-red-600">{errors.name}</p>
                                )}
                            </div>

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

                            {/* Confirm Password */}
                            <div>
                                <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password
                                </label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    value={data.password_confirmation}
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    className={`w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition ${
                                        errors.password_confirmation ? 'border-red-500' : 'border-gray-300'
                                    }`}
                                    placeholder="••••••••"
                                />
                                {errors.password_confirmation && (
                                    <p className="mt-1 text-sm text-red-600">{errors.password_confirmation}</p>
                                )}
                            </div>

                            {/* Terms */}
                            <div className="text-xs text-gray-600">
                                By creating an account, you agree to our{' '}
                                <Link href="#" className="text-blue-600 hover:text-blue-700">Terms of Service</Link>
                                {' '}and{' '}
                                <Link href="#" className="text-blue-600 hover:text-blue-700">Privacy Policy</Link>.
                            </div>

                            {/* Submit Button */}
                            <button
                                type="submit"
                                disabled={processing}
                                aria-busy={processing}
                                className="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {processing ? 'Creating account...' : 'Create account'}
                            </button>
                        </form>

                        {/* Login Link */}
                        <div className="mt-6 text-center text-sm text-gray-600">
                            Already have an account?{' '}
                            <Link href="/login" className="text-blue-600 hover:text-blue-700 font-semibold">
                                Sign in
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
