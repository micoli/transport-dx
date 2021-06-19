import React from 'react';
import PropTypes from 'prop-types';
import {
  Box,
  Drawer,
  List,
  makeStyles, Container, Grid
} from '@material-ui/core';
import NavItem from './NavItem';
import GmailTreeView from './GmailTreeView';

const useStyles = makeStyles(() => ({
  desktopDrawer: {
    width: 600,
    top: 64,
    height: 'calc(100% - 64px)'
  },
  tree: {
    top: 64,
    height: 'calc(100% - 64px)'
  },
  messages: {
    top: 64,
    height: 'calc(100% - 64px)'
  },
  avatar: {
    cursor: 'pointer',
    width: 64,
    height: 64
  }
}));

const NavBar = ({messages, groups, onMessageSelected}) => {
  const classes = useStyles();
  console.log(messages);
  return (
    <Drawer
      anchor="left"
      classes={{paper: classes.desktopDrawer}}
      open
      variant="persistent"
    >
      <Container maxWidth="xl">
        <Grid container spacing={3}>
          <Grid item lg={4} md={4} xl={4} xs={4}>
            <GmailTreeView
              height="100%"
              display="flex"
              flexDirection="column"
              groups={groups}
              className={classes.tree}
            />
          </Grid>
          <Grid item lg={8} md={8} xl={8} xs={8}>
            <Box
              height="100%"
              display="flex"
              flexDirection="column"
              className={classes.messages}
            >
              <List>
                {messages && messages.map((message) => (
                  <NavItem
                    key={message.id}
                    message={message}
                    onMessageSelected={onMessageSelected}
                  />
                ))}
              </List>
            </Box>
          </Grid>
        </Grid>
      </Container>

    </Drawer>
  );
};

NavBar.propTypes = {
  messages: PropTypes.array.isRequired,
  groups: PropTypes.array.isRequired,
  onMessageSelected: PropTypes.func.isRequired
};

NavBar.defaultProps = {};

export default NavBar;
