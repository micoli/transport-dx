import React, {useState} from 'react';
import PropTypes from 'prop-types';
import {
  Box,
  Drawer,
  List,
  makeStyles, Container, Grid, FormControl, InputLabel, InputAdornment, IconButton, Input
} from '@material-ui/core';
import {Clear} from "@material-ui/icons";
import NavItem from './NavItem';
import GmailTreeView from './GmailTreeView';
import {Message} from "../../../graphQL/generated/graphqlRequest";

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
  },
  root: {
    display: 'flex',
    flexWrap: 'wrap',
  },
  textField: {
    width: '100%',
  },
}));

const NavBar = ({messages, groups, onFilterChanged, selectedMessage, onMessageSelected, onGroupSelected, filterUsed}) => {
  const classes = useStyles();
  const [filter, setFilter] = useState('');

  const filterMessage = (message: Message) => {
    if (filterUsed === '') {
      return true;
    }
    return message.subject.match(filterUsed);
  }

  return (
    <Drawer
      anchor="left"
      classes={{paper: classes.desktopDrawer}}
      open
      variant="persistent"
    >
      <Container maxWidth="xl">
        <Grid container spacing={3}>
          <Grid item xs={12} hidden>
            <FormControl className={classes.textField}>
              <InputLabel htmlFor="adornment-filter">Filter</InputLabel>
              <Input
                id="adornment-filter"
                type="text"
                value={filter}
                fullWidth
                onChange={(event) => {
                  onFilterChanged(event.target.value);
                  setFilter(event.target.value);
                }}
                endAdornment={(
                  <InputAdornment position="end">
                    <IconButton
                      aria-label="Clear filter"
                      onClick={()=>setFilter('')}
                      edge="end"
                      disabled={filter === ''}
                    >
                      <Clear />
                    </IconButton>
                  </InputAdornment>
                )}
              />
            </FormControl>
          </Grid>
          <Grid item xs={4}>
            <Box
              height="100%"
              display="flex"
              flexDirection="column"
            >
              <GmailTreeView
                groups={groups}
                className={classes.tree}
                onGroupSelected={onGroupSelected}
              />
            </Box>
          </Grid>
          <Grid item xs={8}>
            <Box
              height="100%"
              display="flex"
              flexDirection="column"
              className={classes.messages}
            >
              <List>
                {messages && messages.filter(filterMessage).map((message) => (
                  <NavItem
                    key={message.id}
                    message={message}
                    isSelected={selectedMessage === message}
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
  filterUsed: PropTypes.string.isRequired,
  onFilterChanged: PropTypes.func,
  onMessageSelected: PropTypes.func.isRequired,
  onGroupSelected: PropTypes.func.isRequired,
  selectedMessage: PropTypes.object
};

NavBar.defaultProps = {};

export default NavBar;
