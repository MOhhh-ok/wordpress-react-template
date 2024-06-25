import React from 'react';
import { createRoot } from "react-dom/client";

export function addRenderComponentWithProps(className: string, Component: React.FC<any>) {
    document.addEventListener('DOMContentLoaded', () => {
        renderComponentWithProps(className, Component);
    });
}

export function renderComponentWithProps(className: string, Component: React.FC<any>) {
    const containers = document.querySelectorAll('.' + className) || [];
    containers.forEach(container => {
        try {
            const propsJson = container.getAttribute('data-props') || '{}'
            const props = JSON.parse(propsJson);
            createRoot(container).render(<Component {...props} />);
        } catch (e: any) {
            console.log(className, container.getAttribute('data-props'))
            console.error(e);
        }
    });
}
