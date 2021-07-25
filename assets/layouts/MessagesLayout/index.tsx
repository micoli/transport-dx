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
    paddingLeft: 900
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
  const [checkedMessages, setCheckedMessages] = useState({});
  const [groups, setGroups] = useState([]);
  const [loading, setLoading] = useState(false);
  const [filter, setFilter] = useState('');
  const [groupName, setGroupName] = useState('');
  const [selectedMessage, setSelectedMessage] = useState(null);

  const refreshGroups = () => {
    graphQLClient
      .request<GroupsQuery>(GroupsDocument)
      .then((groupsDocument) => setGroups(groupsDocument.groups));
  }

  const loadMessages = (newGroupName = null) => {
    setLoading(true);
    graphQLClient
      .request<MessagesQuery>(MessagesDocument,{
        groupName : newGroupName ?? groupName
      })
      .then((messagesDocument) => {
        setLoading(false);
        setMessages(messagesDocument.messages)
        console.log(messagesDocument.messages.length);
        setCheckedMessages(messagesDocument.messages.reduce((accumulator, message) => {
          accumulator[message.id] = false;
          return accumulator;
        }, {}))
        if (messagesDocument.messages.length > 0) {
          setSelectedMessage(messagesDocument.messages[0])
        }
      });
    refreshGroups();
  };

  const onGroupSelected = (changedGroupName) => {
    loadMessages(changedGroupName);
    setGroupName(changedGroupName);
  }

  const toggleChecked = (message, checked) => {
    checkedMessages[message.id] = checked;
    setCheckedMessages({...checkedMessages})
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
        filterUsed={filter}
        onMessageSelected={setSelectedMessage}
        selectedMessage={selectedMessage}
        checkedMessages={checkedMessages}
        onFilterChanged={onFilterChanged}
        toggleChecked={toggleChecked}
        setCheckedMessages={setCheckedMessages}
        onGroupSelected={onGroupSelected}
        loadMessages={loadMessages}
        refreshGroups={refreshGroups}
        loading={loading}
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
