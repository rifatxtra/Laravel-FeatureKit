import React from "react";
import { Head, usePage } from "@inertiajs/react";

export default function SeoHead({
    title,
    description,
    keywords,
    image,
    type = "website",
}) {
    const { props } = usePage();
    const appName = props.app?.app_name || "Feature Kit";
    const metaTitle = title ? `${title} | ${appName}` : appName;

    return (
        <Head>
            <title>{metaTitle}</title>
            {description && <meta name="description" content={description} />}
            {keywords && <meta name="keywords" content={keywords} />}

            {/* Open Graph */}
            {/* {title && <meta property="og:title" content={title} />}
            {description && (
                <meta property="og:description" content={description} />
            )}
            {image && <meta property="og:image" content={image} />}
            <meta property="og:type" content={type} /> */}

            {/* Twitter Card */}
            {/* <meta name="twitter:card" content="summary_large_image" />
            {title && <meta name="twitter:title" content={title} />}
            {description && (
                <meta name="twitter:description" content={description} />
            )}
            {image && <meta name="twitter:image" content={image} />} */}
        </Head>
    );
}
