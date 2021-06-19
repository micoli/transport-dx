import React from 'react';
import { Link as RouterLink } from 'react-router-dom';
import BrightnessHighIcon from '@material-ui/icons/BrightnessHigh';
import Brightness3Icon from '@material-ui/icons/Brightness3';
import {
  AppBar,
  Box,
  Hidden,
  IconButton,
  Toolbar,
  Typography,
} from '@material-ui/core';
import Logo from '../../components/Logo';
import PrefersDarkModeContext from '../../Context/PrefersDarkModeContext';
import { initialDarkMode, setInitialDarkMode } from '../../components/DarkMode';

interface Props {
}
interface State {
  darkMode: boolean;
}

export default class TopBar extends React.Component<Props, State> {
  constructor(props) {
    super(props);
    this.state = {
      darkMode: initialDarkMode()
    };
  }

  render() {
    const {
      ...rest
    } = this.props;
    const {
      darkMode
    } = this.state;

    return (
      <AppBar
        elevation={0}
        {...rest}
      >
        <Toolbar>
          <RouterLink to="/">
            <Logo />
          </RouterLink>
          <Box flexGrow={1}>
            <Typography variant="h4" style={{ paddingLeft: 20 }}>
              MailProxy
            </Typography>
          </Box>
          <Hidden mdDown>
            <PrefersDarkModeContext.Consumer>
              {(context) => (
                <IconButton
                  color="inherit"
                  onClick={() => {
                    const newDarkMode = !darkMode;
                    context.setPrefersDarkMode(newDarkMode);
                    setInitialDarkMode(newDarkMode);
                    this.setState({ darkMode: newDarkMode });
                  }}
                >
                  {darkMode ? <Brightness3Icon /> : <BrightnessHighIcon />}
                </IconButton>
              )}
            </PrefersDarkModeContext.Consumer>
          </Hidden>
        </Toolbar>
      </AppBar>
    );
  }
}
