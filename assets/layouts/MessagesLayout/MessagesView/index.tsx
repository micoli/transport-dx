import React from 'react';
import { Container, Grid, makeStyles } from '@material-ui/core';
import PropTypes from 'prop-types';
import MessageView from './MessageView';

const useStyles = makeStyles((theme) => ({
  root: {
    backgroundColor: theme.palette.background.default,
    minHeight: '100%',
    paddingBottom: theme.spacing(3),
    paddingTop: theme.spacing(3)
  }
}));

const MessagesView = ({ message }) => {
  const classes = useStyles();
  return (
    <Container maxWidth={false} className={classes.root}>
      <Grid container spacing={3}>
        <Grid item xs={12}>
          {message && <MessageView messageId={message.id} />}
        </Grid>
      </Grid>
    </Container>
  );
};
MessagesView.propTypes = {
  message: PropTypes.object,
};

export default MessagesView;
