import React, {useState} from 'react';
import PropTypes from 'prop-types';
import {
  Box,
  Drawer,
  List,
  makeStyles,
  Container,
  Grid,
  FormControl,
  InputLabel,
  InputAdornment,
  IconButton,
  Input,
  Switch,
  FormControlLabel, Toolbar,
} from '@material-ui/core';
import {Clear, DeleteOutlined} from "@material-ui/icons";
import DeleteIcon from "@material-ui/icons/Delete";
import { MuiTriStateCheckbox } from 'mui-tri-state-checkbox'
import NavItem from './NavItem';
import GmailTreeView from './GmailTreeView';
import {Message, DeleteMessageByIdMutation, DeleteMessageByIdDocument} from "../../../graphQL/generated/graphqlRequest";
import {graphQLClient} from '../../../graphQL/GraphQL';

const useStyles = makeStyles(() => ({
  desktopDrawer: {
    width: 900,
    top: 64,
    height: 'calc(100% - 64px)'
  },
  tree: {
    top: 64,
    height: 'calc(100% - 64px)',
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

const NavBar = ({
  messages,
  groups,
  onFilterChanged,
  selectedMessage,
  onMessageSelected,
  onGroupSelected,
  filterUsed,
  checkedMessages,
  toggleChecked,
  setCheckedMessages,
  loadMessages,
  refreshGroups,
  loading
}) => {
  const classes = useStyles();
  const [filter, setFilter] = useState('');
  const [multipleSelectionEnabled, setMultipleSelectionEnabled] = useState(false);

  const numberOfMessagesSelected = Object.keys(checkedMessages).filter((messageId)=>checkedMessages[messageId]).length;
  const allMessageAreChecked = numberOfMessagesSelected === Object.keys(checkedMessages).length ;
  const oneOfMessageIsChecked = numberOfMessagesSelected > 0 && !allMessageAreChecked;

  const filterMessage = (message: Message) => {
    if (filterUsed === '') {
      return true;
    }
    return message.subject.match(filterUsed);
  }

  const deleteCheckedMessages = () => {
    Promise.all(
      Object
        .keys(checkedMessages)
        .filter((messageId) => checkedMessages[messageId])
        .map((messageId)=> graphQLClient.request<DeleteMessageByIdMutation>(DeleteMessageByIdDocument, {messageId}))
    ).then(() => loadMessages());
  }

  const changeCheckAll = () =>{
    setCheckedMessages(
      messages.reduce((accumulator, message) => {
        accumulator[message.id] = oneOfMessageIsChecked ? false : !allMessageAreChecked;
        return accumulator;
      }, {})
    );
  }

  return (
    <Drawer
      anchor="left"
      classes={{paper: classes.desktopDrawer}}
      open
      variant="persistent"
    >
      <Container
        maxWidth="xl"
        style={{paddingLeft: 5}}
      >
        <Grid container spacing={1}>
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
            <GmailTreeView
              groups={groups}
              className={classes.tree}
              onGroupSelected={onGroupSelected}
            />
          </Grid>
          <Grid item xs={8}>
            <Toolbar>
              <FormControlLabel
                control={(
                  <Switch
                    checked={multipleSelectionEnabled}
                    onChange={(event, value) => setMultipleSelectionEnabled(value)}
                    name="checkedB"
                    color="primary"
                  />
                )}
                label="Multiple Selection"
              />
              {multipleSelectionEnabled && (
                <>
                  <MuiTriStateCheckbox
                    edge="start"
                    tabIndex={-1}
                    checked={oneOfMessageIsChecked ? null : allMessageAreChecked}
                    color="primary"
                    onClick={(event) => {
                      changeCheckAll()
                      event.preventDefault();
                    }}
                  />
                  {numberOfMessagesSelected > 0 ? (
                    <DeleteIcon style={{cursor: "pointer"}} onClick={deleteCheckedMessages} />
                  ) : (
                    <DeleteOutlined />
                  )}
                </>
              )}
            </Toolbar>
            <Box
              height="100%"
              display="flex"
              flexDirection="column"
              className={classes.messages}
            >
              <List>
                {!loading && messages && messages.filter(filterMessage).map((message) => (
                  <NavItem
                    key={message.id}
                    message={message}
                    isSelected={selectedMessage === message}
                    onMessageSelected={onMessageSelected}
                    selectionEnabled={multipleSelectionEnabled}
                    checked={checkedMessages[message.id]}
                    toggleChecked={toggleChecked}
                    refreshGroups={refreshGroups}
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
  selectedMessage: PropTypes.object,
  toggleChecked: PropTypes.func.isRequired,
  setCheckedMessages: PropTypes.func.isRequired,
  loadMessages: PropTypes.func.isRequired,
  checkedMessages: PropTypes.object,
  loading: PropTypes.bool.isRequired,
  refreshGroups: PropTypes.func.isRequired,
};

NavBar.defaultProps = {};

export default NavBar;
