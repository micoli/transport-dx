import React, {useState} from 'react';
import {Link as RouterLink} from 'react-router-dom';
import BrightnessHighIcon from '@material-ui/icons/BrightnessHigh';
import Brightness3Icon from '@material-ui/icons/Brightness3';
import SearchIcon from '@material-ui/icons/Search';
import {makeStyles} from "@material-ui/styles";
import {Clear, ClearOutlined} from "@material-ui/icons";
import {
  AppBar,
  Box, createStyles, fade,
  Hidden,
  IconButton, InputAdornment, InputBase, Theme,
  Toolbar,
  Typography,
} from '@material-ui/core';
import Logo from '../../components/Logo';
import PrefersDarkModeContext from '../../Context/PrefersDarkModeContext';
import {initialDarkMode, setInitialDarkMode} from '../../components/DarkMode';
import PropTypes from "prop-types";
import NavBar from "./NavBar";

const useStyles = makeStyles((theme: Theme) =>
  createStyles({
    title: {
      flexGrow: 1,
      display: 'none',
      [theme.breakpoints.up('sm')]: {
        display: 'block',
      },
    },
    search: {
      position: 'relative',
      borderRadius: theme.shape.borderRadius,
      backgroundColor: fade(theme.palette.common.white, 0.15),
      '&:hover': {
        backgroundColor: fade(theme.palette.common.white, 0.25),
      },
      marginLeft: 0,
      width: '100%',
      [theme.breakpoints.up('sm')]: {
        marginLeft: theme.spacing(1),
        width: 'auto',
      },
    },
    searchIcon: {
      padding: theme.spacing(0, 2),
      height: '100%',
      position: 'absolute',
      pointerEvents: 'none',
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
    },
    inputRoot: {
      color: 'inherit',
    },
    inputInput: {
      padding: theme.spacing(1, 1, 1, 0),
      // vertical padding + font size from searchIcon
      paddingLeft: `calc(1em + ${theme.spacing(4)}px)`,
      transition: theme.transitions.create('width'),
      width: '100%',
      [theme.breakpoints.up('sm')]: {
        width: '12ch',
        '&:focus': {
          width: '30ch',
        },
      },
    },
  }),
);
export default function TopBar({onFilterChanged}) {
  const classes = useStyles();
  const [darkMode, SetDarkMode] = useState(initialDarkMode());
  const [filter, setFilter] = useState('');
  return (
    <AppBar
      elevation={0}
    >
      <Toolbar>
        <RouterLink to="/">
          <Logo />
        </RouterLink>
        <Box flexGrow={1}>
          <Typography variant="h4" style={{paddingLeft: 20}}>
            Transport DX
          </Typography>
        </Box>
        <div className={classes.search}>
          <div className={classes.searchIcon}>
            <SearchIcon />
          </div>
          <InputBase
            placeholder="Filter…"
            classes={{
              root: classes.inputRoot,
              input: classes.inputInput,
            }}
            value={filter}
            inputProps={{'aria-label': 'filter'}}
            onChange={(event) => {
              onFilterChanged(event.target.value);
              setFilter(event.target.value);
            }}
            endAdornment={(
              <InputAdornment position="end">
                {filter !== '' && (
                  <IconButton
                    aria-label="Clear filter"
                    onClick={()=>{
                      onFilterChanged('');
                      setFilter('');
                    }}
                    edge="end"
                  >
                    <ClearOutlined />
                  </IconButton>
                )}
              </InputAdornment>
            )}
          />
        </div>
        {' '}
        <Hidden mdDown>
          <PrefersDarkModeContext.Consumer>
            {(context) => (
              <IconButton
                color="inherit"
                onClick={() => {
                  const newDarkMode = !darkMode;
                  context.setPrefersDarkMode(newDarkMode);
                  setInitialDarkMode(newDarkMode);
                  SetDarkMode(newDarkMode);
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
TopBar.propTypes = {
  onFilterChanged: PropTypes.func.isRequired,
}
