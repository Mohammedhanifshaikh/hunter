import React from "react";

interface LoaderProps {
  active: boolean;
}

const TopProgressBar: React.FC<LoaderProps> = ({ active }) => {
  return (
    <div className="fixed top-0 left-0 right-0 z-[70] pointer-events-none">
      <div
        className={`h-1 bg-accent transition-all duration-300 ${active ? 'w-full animate-[progress_1.2s_ease-in-out_infinite]' : 'w-0'}`}
        style={{ boxShadow: '0 0 12px rgba(255,140,0,0.6)' }}
      />
      <style>{`
        @keyframes progress {
          0% { transform: translateX(-100%); width: 30%; }
          50% { transform: translateX(20%); width: 60%; }
          100% { transform: translateX(100%); width: 30%; }
        }
      `}</style>
    </div>
  );
};

const LoaderOverlay: React.FC<LoaderProps> = ({ active }) => {
  if (!active) return null;
  return (
    <div className="fixed inset-0 z-[65] bg-background/40 backdrop-blur-sm pointer-events-none flex items-center justify-center">
      <div className="relative w-14 h-14">
        <span className="absolute inset-0 rounded-full border-4 border-accent/30 border-t-accent animate-spin" />
        <span className="absolute inset-2 rounded-full border-4 border-primary/20 border-b-primary animate-[spin_1.2s_linear_infinite_reverse]" />
      </div>
    </div>
  );
};

const Loader: React.FC<LoaderProps> = ({ active }) => (
  <>
    <TopProgressBar active={active} />
    <LoaderOverlay active={active} />
  </>
);

export default Loader;
