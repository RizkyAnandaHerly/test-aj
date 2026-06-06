import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import LandingPage from './components/LandingPage';

const container = document.getElementById('landing-app');
if (container) {
    const root = createRoot(container);
    root.render(<LandingPage />);
}
