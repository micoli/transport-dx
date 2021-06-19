import React, {Component} from 'react';
import PerfectScrollbar from 'react-perfect-scrollbar';
import {
  Box,
  Card,
  CardHeader,
  Divider,
} from '@material-ui/core';
import {graphQLClient} from '../../../graphQL/GraphQL';
import {
  Message,
  MessageDocument,
  MessageQuery,
} from '../../../graphQL/generated/graphqlRequest';

interface Props {
  messageId: string
}

interface State {
  messageDetail?: Message
}

export default class MessageView extends Component<Props, State> {
  constructor(props) {
    super(props);
    this.state = {
      messageDetail: null
    };
  }

  componentDidMount() {
    this.loadMessage();
  }

  // eslint-disable-next-line no-unused-vars
  componentDidUpdate(prevProps, prevState, snapshot) {
    const { messageId } = this.props;
    if (messageId !== prevProps.messageId) {
      console.log(messageId);
      this.loadMessage();
    }
  }

  loadMessage = () => {
    const {messageId} = this.props;
    graphQLClient.request<MessageQuery>(MessageDocument, {messageId})
      .then((messageQuery) => {
        console.log(messageQuery);
        this.setState({
          messageDetail: messageQuery.message
        });
      });
  };

  render() {
    const {messageDetail} = this.state;

    if (!messageDetail) {
      return <>no message</>;
    }
    return (
      <Card>
        <CardHeader title={messageDetail.subject} />
        <Divider />
        <PerfectScrollbar>
          <Box minWidth={200} maxWidth={800}>
            {messageDetail.id}
            {messageDetail.recipients.map((recipient) => (
              <span key={recipient.address}>
                {recipient.address}
                {recipient.display && (
                  <>
                    &lt;
                    {recipient.display}
                    &gt;
                  </>
                )}
              </span>
            ))}
            <br />
            {messageDetail.date}
            {JSON.stringify(messageDetail)}
          </Box>
        </PerfectScrollbar>
      </Card>
    );
  }
}
