import { Head, Link } from '@inertiajs/react';
import { useAppConfig } from '@/hooks/useSharedProps';

export default function Welcome() {
    const app = useAppConfig();

    return (
        <>
            <Head title="Welcome" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
                {/* Header */}
                <header className="container mx-auto px-4 py-6">
                    <div className="flex justify-between items-center">
                        <h1 className="text-2xl font-bold text-gray-900">{app?.name || 'Laravel FeatureKit'}</h1>
                        <nav className="space-x-4">
                            <Link href="/login" className="text-gray-600 hover:text-gray-900">Login</Link>
                            <Link href="/register" className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Get Started
                            </Link>
                        </nav>
                    </div>
                </header>

                {/* Hero Section */}
                <main className="container mx-auto px-4 py-20">
                    <div className="max-w-4xl mx-auto text-center">
                        <div className="mb-8">
                            <span className="inline-block bg-blue-100 text-blue-800 text-sm font-semibold px-4 py-2 rounded-full mb-4">
                                Feature-Based Architecture
                            </span>
                        </div>
                        
                        <h2 className="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                            Build Modern Laravel Apps
                            <span className="block text-blue-600 mt-2">With React & Inertia</span>
                        </h2>
                        
                        <p className="text-xl text-gray-600 mb-12 max-w-2xl mx-auto">
                            A starter template featuring Laravel 12, React 19, Inertia.js, and Tailwind CSS 4. 
                            Organized by features, not layers. Ready to scale.
                        </p>

                        <div className="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                            <a 
                                href="https://github.com/rifatxtra/Laravel-FeatureKit" 
                                target="_blank"
                                rel="noopener noreferrer"
                                className="bg-gray-900 text-white px-8 py-4 rounded-lg font-semibold hover:bg-gray-800 transition inline-flex items-center justify-center"
                            >
                                <svg className="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path fillRule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clipRule="evenodd" />
                                </svg>
                                View on GitHub
                            </a>
                            <a 
                                href="#features" 
                                className="bg-white text-gray-900 px-8 py-4 rounded-lg font-semibold border-2 border-gray-300 hover:border-gray-400 transition"
                            >
                                Learn More
                            </a>
                        </div>

                        {/* Feature Grid */}
                        <div id="features" className="grid md:grid-cols-3 gap-8 text-left mt-20">
                            <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                    <svg className="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Feature-Based Structure</h3>
                                <p className="text-gray-600">Organize code by features, not layers. Better scalability and team collaboration.</p>
                            </div>

                            <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                                    <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Modern Tech Stack</h3>
                                <p className="text-gray-600">Laravel 12, React 19, Inertia.js, and Tailwind CSS 4. Lightning fast.</p>
                            </div>

                            <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                                <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                                    <svg className="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                </div>
                                <h3 className="text-xl font-semibold mb-2">Developer Experience</h3>
                                <p className="text-gray-600">Artisan commands, custom hooks, SEO-ready, and comprehensive documentation.</p>
                            </div>
                        </div>

                        {/* Quick Start */}
                        <div className="mt-20 bg-gray-900 text-white p-8 rounded-xl">
                            <h3 className="text-2xl font-semibold mb-4">Quick Start</h3>
                            <div className="bg-gray-800 p-4 rounded-lg text-left font-mono text-sm overflow-x-auto">
                                <div className="text-gray-400"># Create a new feature</div>
                                <div className="text-green-400">php artisan make:feature Blog</div>
                                <div className="mt-2 text-gray-400"># With API support</div>
                                <div className="text-green-400">php artisan make:feature Products --api</div>
                                <div className="mt-2 text-gray-400"># List all features</div>
                                <div className="text-green-400">php artisan feature:list</div>
                            </div>
                        </div>
                    </div>
                </main>

                {/* Footer */}
                <footer className="border-t border-gray-200 mt-20">
                    <div className="container mx-auto px-4 py-8">
                        <div className="flex flex-col md:flex-row justify-between items-center gap-4">
                            <div className="text-center md:text-left">
                                <p className="text-gray-600 mb-2">
                                    Created by <a href="https://rifatxtra.com/" target="_blank" rel="noopener noreferrer" className="text-blue-600 hover:text-blue-700 font-semibold">Md. Rashedul Islam</a>
                                </p>
                                <div className="flex gap-4 justify-center md:justify-start">
                                    <a href="https://github.com/rifatxtra/Laravel-FeatureKit" target="_blank" rel="noopener noreferrer" className="text-gray-500 hover:text-gray-700">
                                        <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path fillRule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clipRule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="https://rifatxtra.com/" target="_blank" rel="noopener noreferrer" className="text-gray-500 hover:text-gray-700">
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div className="text-gray-500 text-sm">
                                <p>&copy; {new Date().getFullYear()} Laravel FeatureKit. Open Source.</p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}
