import 'react-perfect-scrollbar/dist/css/styles.css';
import React, { useState } from 'react';
import { createMuiTheme, ThemeProvider } from '@material-ui/core/styles';
import CssBaseline from '@material-ui/core/CssBaseline';
import GlobalStyles from './components/GlobalStyles';
import PrefersDarkModeContext from './Context/PrefersDarkModeContext';
import { initialDarkMode } from './components/DarkMode';
import DashboardLayout from './layouts/MessagesLayout';

const App = () => {
  const [prefersDarkMode, setPrefersDarkMode] = useState(initialDarkMode());
  const theme = React.useMemo(() => createMuiTheme({
    palette: {
      type: prefersDarkMode ? 'dark' : 'light',
    },
  }), [prefersDarkMode]);

  return (
    <PrefersDarkModeContext.Provider value={{ prefersDarkMode, setPrefersDarkMode }}>
      <ThemeProvider theme={theme}>
        <CssBaseline />
        <GlobalStyles />
        <DashboardLayout />
      </ThemeProvider>
    </PrefersDarkModeContext.Provider>
  );
};

export default App;
