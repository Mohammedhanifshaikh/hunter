import { useEffect, useMemo, useRef, useState } from "react";

interface CountUpProps {
  end: number;
  durationMs?: number;
  prefix?: string;
  suffix?: string;
  decimals?: number;
  separator?: string;
  startOnView?: boolean;
  className?: string;
  compact?: boolean; // use K/M/B notation
}

const easeOutCubic = (t: number) => 1 - Math.pow(1 - t, 3);

const CountUp = ({
  end,
  durationMs = 1500,
  prefix = "",
  suffix = "",
  decimals = 0,
  separator = ",",
  startOnView = true,
  className,
  compact = false,
}: CountUpProps) => {
  const [value, setValue] = useState(0);
  const [started, setStarted] = useState(!startOnView);
  const ref = useRef<HTMLSpanElement | null>(null);

  // start when visible
  useEffect(() => {
    if (!startOnView) return;
    const el = ref.current;
    if (!el) return;
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            setStarted(true);
            observer.disconnect();
          }
        });
      },
      { threshold: 0.3 },
    );
    observer.observe(el);
    return () => observer.disconnect();
  }, [startOnView]);

  useEffect(() => {
    if (!started) return;
    let raf = 0;
    const start = performance.now();

    const tick = (now: number) => {
      const elapsed = now - start;
      const progress = Math.min(1, elapsed / durationMs);
      const eased = easeOutCubic(progress);
      setValue(end * eased);
      if (progress < 1) raf = requestAnimationFrame(tick);
    };

    raf = requestAnimationFrame(tick);
    return () => cancelAnimationFrame(raf);
  }, [started, end, durationMs]);

  const formatted = useMemo(() => {
    const n = value;
    const formatter = new Intl.NumberFormat(undefined, {
      notation: compact ? "compact" : undefined,
      compactDisplay: compact ? "short" : undefined,
      minimumFractionDigits: decimals,
      maximumFractionDigits: decimals,
    } as Intl.NumberFormatOptions);
    let s = formatter.format(n);
    if (!compact && separator !== ",") {
      s = s.replace(/,/g, separator);
    }
    return `${prefix}${s}${suffix}`;
  }, [value, prefix, suffix, decimals, separator, compact]);

  return (
    <span ref={ref} className={className} aria-label={`${prefix}${end}${suffix}`}>
      {formatted}
    </span>
  );
};

export default CountUp;
