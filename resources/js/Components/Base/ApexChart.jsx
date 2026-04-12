import React, { useEffect, useRef } from "react";
import ApexCharts from "apexcharts";

/**
 * ApexChart Component (Vanilla Wrapper)
 * 
 * A reusable React wrapper for the Vanilla ApexCharts library.
 * This avoids peer dependency conflicts with React 19.
 * 
 * @param {Object} options - ApexCharts options object.
 * @param {Array} series - ApexCharts series data.
 * @param {string} type - Chart type (line, area, bar, pie, etc).
 * @param {string|number} height - Chart height.
 */
export default function ApexChart({ options = {}, series = [], type = "line", height = 350 }) {
    const chartRef = useRef(null);
    const chartInstance = useRef(null);

    useEffect(() => {
        if (chartRef.current) {
            // Initialize the chart
            chartInstance.current = new ApexCharts(chartRef.current, {
                ...options,
                series,
                chart: {
                    ...options.chart,
                    type,
                    height,
                },
            });

            chartInstance.current.render();
        }

        // Cleanup on unmount
        return () => {
            if (chartInstance.current) {
                chartInstance.current.destroy();
            }
        };
    }, []);

    // Update series when data changes
    useEffect(() => {
        if (chartInstance.current && series) {
            chartInstance.current.updateSeries(series, true);
        }
    }, [series]);

    // Update options when they change
    useEffect(() => {
        if (chartInstance.current && options) {
            chartInstance.current.updateOptions(options, false, true);
        }
    }, [options]);

    return <div ref={chartRef} />;
}
