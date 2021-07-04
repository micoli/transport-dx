import React, {useEffect, useState} from 'react';
import {makeStyles} from '@material-ui/core';
import debounce from 'lodash/debounce';
import NavBar from './NavBar';
import TopBar from './TopBar';
import MessagesView from './MessagesView';
import {MessagesQuery, MessagesDocument, GroupsQuery, GroupsDocument} from '../../graphQL/generated/graphqlRequest';
import {graphQLClient} from '../../graphQL/GraphQL';

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
    paddingLeft: 600
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
  const [filter, setFilter] = useState('');
  const [groupName, setGroupName] = useState('');
  const [selectedMessage, setSelectedMessage] = useState(null);

  const loadMessages = () => {
    graphQLClient.request<MessagesQuery>(MessagesDocument,{groupName})
      .then((messagesDocument) => {
        setMessages(messagesDocument.messages)
        if (messagesDocument.messages.length > 0) {
          setSelectedMessage(messagesDocument.messages[0])
        }
      });
    graphQLClient.request<GroupsQuery>(GroupsDocument)
      .then((groupsDocument) => setGroups(groupsDocument.groups));
  };
  const onGroupSelected = (changedGroupName) => {
    setGroupName(changedGroupName);
    loadMessages();
  }

  const onFilterChanged = debounce((changedFilter)=> {
    setFilter(changedFilter)
  }, 300);

  useEffect(() => {
    loadMessages();
  }, []);

  return (
    <div className={classes.root}>
      <TopBar
        onFilterChanged={onFilterChanged}
      />
      <NavBar
        messages={messages}
        groups={groups}
        onMessageSelected={setSelectedMessage}
        selectedMessage={selectedMessage}
        onFilterChanged={onFilterChanged}
        filterUsed={filter}
        onGroupSelected={onGroupSelected}
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
