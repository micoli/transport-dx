import React from 'react';
import clsx from 'clsx';
import PropTypes, { InferProps } from 'prop-types';
import {
  Chip,
  ListItem, ListItemText,
  makeStyles
} from '@material-ui/core';
import moment from "moment/moment";
import {Paperclip} from "react-feather";
import {PictureInPicture} from "@material-ui/icons";
import MessageDate from "../MessagesView/MessageDate";

const useStyles = makeStyles((theme) => ({
  item: {
    display: 'flex',
    paddingTop: 0,
    paddingBottom: 0
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
  ...rest
}: InferProps<typeof NavItem.propTypes>) => {
  const classes = useStyles();
  return (
    <ListItem
      className={clsx(classes.item, className)}
      disableGutters
      dense
      {...rest}
      onClick={() => onMessageSelected(message)}
    >
      <ListItemText
        primary={(
          <>
            {isSelected ? <strong>{message.subject}</strong> : message.subject}
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
  onMessageSelected: PropTypes.func.isRequired,
};

export default NavItem;
