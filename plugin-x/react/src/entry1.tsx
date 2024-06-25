import React from 'react';
import { addRenderComponentWithProps } from './utils/render';


addRenderComponentWithProps('plugin-x-test', Component);


function Component(props: any) {
    return <div>This is React test. {JSON.stringify(props)}</div>
}