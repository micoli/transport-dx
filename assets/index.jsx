import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter } from 'react-router-dom';
import { svgFavicon } from '@space-kit/hat';
import App from './App';
import RawSvg from './components/LogoPicture';

svgFavicon(RawSvg);

ReactDOM.render((
  <BrowserRouter>
    <App />
  </BrowserRouter>
), document.getElementById('root'));
