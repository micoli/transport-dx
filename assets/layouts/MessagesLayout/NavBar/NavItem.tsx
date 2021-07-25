import React, {useState} from 'react';
import clsx from 'clsx';
import PropTypes, { InferProps } from 'prop-types';
import {
  Checkbox,
  Chip,
  ListItem, ListItemIcon, ListItemText,
  makeStyles
} from '@material-ui/core';
import moment from "moment/moment";
import {Paperclip} from "react-feather";
import {DraftsOutlined, MailOutlineOutlined, PictureInPicture} from "@material-ui/icons";
import MessageDate from "../MessagesView/MessageDate";
import {graphQLClient} from "../../../graphQL/GraphQL";
import {
  ChangeReadStatusMutation, ChangeReadStatusDocument,
} from "../../../graphQL/generated/graphqlRequest";

const useStyles = makeStyles((theme) => ({
  item: {
    display: 'flex',
    paddingTop: 0,
    paddingBottom: 0
  },
  selected:{
    backgroundColor: theme.palette.action.focus
  },
  messageCheck: {
    marginRight: -30,
    marginLeft: 8
  },
  button: {
    color: theme.palette.text.secondary,
    fontWeight: theme.typography.fontWeightMedium,
    justifyContent: 'flex-start',
    letterSpacing: 0,
    padding: '10px 8px',
    textTransform: 'none',
    width: '100%'
  },
  unRead: {
    fontWeight: theme.typography.fontWeightBold,
  },
  icon: {
    marginRight: theme.spacing(1)
  },
  title: {
    marginRight: 'auto'
  },
  active: {
    color: theme.palette.primary.main,
    '& $title': {
      fontWeight: theme.typography.fontWeightMedium
    },
    '& $icon': {
      color: theme.palette.primary.main
    }
  }
}));

const NavItem = ({
  className,
  message,
  isSelected,
  onMessageSelected,
  selectionEnabled,
  toggleChecked,
  checked,
  refreshGroups,
}: InferProps<typeof NavItem.propTypes>) => {
  const classes = useStyles();
  const labelId = `checkbox-list-label-${message.id}`;
  const [isRead, setReadStatus] = useState(message.isRead);

  const changeReadStatusHandler = (readStatus) => {
    graphQLClient.request<ChangeReadStatusMutation>(ChangeReadStatusDocument,{
      messageId: message.id,
      isRead: readStatus
    }).then((result) => {
      if (result.changeReadStatus) {
        setReadStatus(readStatus);
      }
      refreshGroups();
    })
  };

  return (
    <ListItem
      className={clsx(
        classes.item,
        className,
        isSelected && classes.selected,
        !isRead && classes.unRead
      )}
      disableGutters
      dense
      onClick={(event) => {
        changeReadStatusHandler(true);
        onMessageSelected(message);
        event.preventDefault();
      }}
    >
      {selectionEnabled && (
        <ListItemIcon
          className={classes.messageCheck}
        >
          <Checkbox
            edge="start"
            size="small"
            tabIndex={-1}
            inputProps={{ 'aria-labelledby': labelId }}
            checked={checked}
            color="primary"
            onClick={(event) => {
              toggleChecked(message, !checked);
              event.preventDefault();
            }}
          />
        </ListItemIcon>
      )}
      <ListItemText
        style={{cursor: "pointer"}}
        id={labelId}
        primary={(
          <>
            {isRead ? (
              <DraftsOutlined
                onClick={(event) => {
                  changeReadStatusHandler(false)
                  event.preventDefault();
                }}
              />
            ) : (
              <MailOutlineOutlined
                onClick={(event) => {
                  changeReadStatusHandler(true);
                  event.preventDefault();
                }}
              />
            )}
            {' '}
            <span
              className={clsx(
                !isRead && classes.unRead
              )}
            >
              {message.subject}
            </span>
            {message.group && <Chip label={message.group} />}
            {message.hasDownloadableAttachments && <Paperclip style={{height:"2vh"}} />}
            {message.hasInlinedAttachments && <PictureInPicture style={{height:"2vh"}} />}
          </>
        )}
        secondary={message.from.address}
      />
      <MessageDate date={moment(message.date)} />
    </ListItem>
  );
}

NavItem.propTypes = {
  message: PropTypes.object,
  className: PropTypes.string,
  isSelected: PropTypes.bool,
  checked: PropTypes.bool,
  selectionEnabled: PropTypes.bool,
  onMessageSelected: PropTypes.func.isRequired,
  refreshGroups: PropTypes.func.isRequired,
  toggleChecked: PropTypes.func.isRequired,
};

export default NavItem;
