import React, { useEffect, useState } from 'react';
import { makeStyles } from '@material-ui/core';
import NavBar from './NavBar';
import TopBar from './TopBar';
import MessagesView from './MessagesView';
import {MessagesQuery, MessagesDocument, GroupsQuery, GroupsDocument} from '../../graphQL/generated/graphqlRequest';
import { graphQLClient } from '../../graphQL/GraphQL';

const useStyles = makeStyles((theme) => ({
  root: {
    backgroundColor: theme.palette.background.default,
    display: 'flex',
    height: '100%',
    overflow: 'hidden',
    width: '100%'
  },
  wrapper: {
    display: 'flex',
    flex: '1 1 auto',
    overflow: 'hidden',
    paddingTop: 64,
    [theme.breakpoints.up('lg')]: {
      paddingLeft: 512
    }
  },
  contentContainer: {
    display: 'flex',
    flex: '1 1 auto',
    overflow: 'hidden'
  },
  content: {
    flex: '1 1 auto',
    height: '100%',
    overflow: 'auto'
  }
}));

const DashboardLayout = () => {
  const classes = useStyles();
  const [messages, setMessages] = useState([]);
  const [groups, setGroups] = useState([]);
  const [selectedMessage, onMessageSelected] = useState(null);

  const loadMessages = () => {
    graphQLClient.request<MessagesQuery>(MessagesDocument)
      .then((messagesDocument) => setMessages(messagesDocument.messages));
    graphQLClient.request<GroupsQuery>(GroupsDocument)
      .then((groupsDocument) => setGroups(groupsDocument.groups));
  };

  useEffect(() => {
    loadMessages();
  }, []);

  return (
    <div className={classes.root}>
      <TopBar />
      <NavBar
        messages={messages}
        groups={groups}
        onMessageSelected={onMessageSelected}
      />
      <div className={classes.wrapper}>
        <div className={classes.contentContainer}>
          <div className={classes.content}>
            <MessagesView message={selectedMessage} />
          </div>
        </div>
      </div>
    </div>
  );
};

export default DashboardLayout;
