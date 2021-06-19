import React from 'react';
import clsx from 'clsx';
import PropTypes, { InferProps } from 'prop-types';
import {
  ListItem, ListItemText,
  makeStyles
} from '@material-ui/core';
import moment from "moment/moment";

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
  onMessageSelected,
  ...rest
}: InferProps<typeof NavItem.propTypes>) => {
  const classes = useStyles();
  return (
    <ListItem
      className={clsx(classes.item, className)}
      disableGutters
      {...rest}
      onClick={() => onMessageSelected(message)}
    >
      <ListItemText
        primary={message.subject}
        secondary={message.from.address}
      />
      {moment(message.date).format('DD/MM/YY HH:mm')}
    </ListItem>
  );
};

NavItem.propTypes = {
  message: PropTypes.object,
  className: PropTypes.string,
  onMessageSelected: PropTypes.func.isRequired,
};

export default NavItem;
