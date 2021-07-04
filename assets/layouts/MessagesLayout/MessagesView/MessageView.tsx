import React, {Component} from 'react';
import {
  Card,
  CardHeader, Chip,
  Grid, Tab, Tabs, Typography,
} from '@material-ui/core';
import moment from "moment/moment";
import {AttachFile, Code, TextFields, ViewHeadline} from "@material-ui/icons";
import {graphQLClient} from '../../../graphQL/GraphQL';
import {
  Message,
  MessageDocument,
  MessageQuery,
} from '../../../graphQL/generated/graphqlRequest';
import EmailAddress from "./EmailAddress";
import MessageDate from "./MessageDate";
import MessageHeaders from "./MessageHeadersView";
import TabPanel from "./TabPanel";
import MessageAttachmentsView from "./MessageAttachmentsView";
import MessageHtmlView from "./MessageHtmlView";

interface Props {
  messageId: string
}

interface State {
  message?: Message,
  tab: number
}

const a11yProps = (index: any) => ({
  id: `simple-tab-${index}`,
  'aria-controls': `simple-tabpanel-${index}`,
});

export default class MessageView extends Component<Props, State> {
  constructor(props) {
    super(props);
    this.state = {
      message: null,
      tab: 0
    };
  }

  componentDidMount() {
    this.loadMessage();
  }

  // eslint-disable-next-line no-unused-vars
  componentDidUpdate(prevProps, prevState, snapshot) {
    const {messageId} = this.props;
    if (messageId !== prevProps.messageId) {
      this.loadMessage();
    }
  }

  loadMessage = () => {
    const {messageId} = this.props;
    graphQLClient.request<MessageQuery>(MessageDocument, {messageId})
      .then((messageQuery) => {
        this.setState({
          message: messageQuery.message
        });
      });
  };

  render() {
    const {message, tab} = this.state;

    if (!message) {
      return <>No selected message</>;
    }

    return (
      <Card>
        <CardHeader title={(
          <>
            {message.subject}
            {message.group && <Chip label={message.group} />}
          </>
        )}
        />
        <Grid container spacing={1}>
          <Grid container item xs={12}>
            <Grid item xs={9}>
              <Typography style={{display: 'inline-block', marginRight:3,marginLeft:12}}>From:</Typography>
              <EmailAddress recipient={message.from} />
            </Grid>
            <Grid item xs={3}>
              <Typography style={{display: 'inline-block', marginRight:3}}>Date:&nbsp;</Typography>
              <MessageDate date={moment(message.date)} />
            </Grid>
          </Grid>
          <Grid container item xs={12}>
            <Grid item xs={12}>
              <Typography style={{display: 'inline-block', marginRight:3, marginLeft:12}}>To:&nbsp;</Typography>
              {message.recipients.map((recipient) => (
                <EmailAddress key={recipient.address} recipient={recipient} />
              ))}
            </Grid>
          </Grid>
        </Grid>
        <Tabs
          value={tab}
          onChange={(event: React.ChangeEvent<{}>, value: number) => this.setState({tab: value})}
          style={{flexDirection: 'row'}}
        >
          <Tab label={<div><Code style={{verticalAlign: 'middle'}} /> Html</div>} {...a11yProps(0)} />
          <Tab label={<div><TextFields style={{verticalAlign: 'middle'}} /> Text</div>} {...a11yProps(1)} />
          <Tab label={<div><ViewHeadline style={{verticalAlign: 'middle'}} /> Headers</div>} {...a11yProps(2)} />
          <Tab label={<div><AttachFile style={{verticalAlign: 'middle'}} /> Attachments</div>} {...a11yProps(3)} />
        </Tabs>
        <TabPanel value={tab} index={0}>
          <MessageHtmlView message={message} />
        </TabPanel>
        <TabPanel value={tab} index={1}>
          {message.text}
        </TabPanel>
        <TabPanel value={tab} index={2}>
          <MessageHeaders message={message} />
        </TabPanel>
        <TabPanel value={tab} index={3}>
          <MessageAttachmentsView message={message} />
        </TabPanel>
      </Card>
    );
  }
}

