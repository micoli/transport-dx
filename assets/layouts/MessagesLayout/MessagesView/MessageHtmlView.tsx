/* eslint-disable react/no-danger */
import React, {useState} from 'react';
import {Button, ButtonGroup} from "@material-ui/core";
import PerfectScrollbar from 'react-perfect-scrollbar';
import {ToggleButton, ToggleButtonGroup} from "@material-ui/lab";
import {
  Message,
} from '../../../graphQL/generated/graphqlRequest';

interface Props {
  message: Message
}
const breakpoints = {
  '280': 280,
  'Scrimpy 340': 340,
  'ExtraSmall 375': 375,
  'Tiny 640': 640,
  'Small 768': 768,
  'Medium 1024': 1024,
  'Large 1280': 1280,
  'ExtraLarge 1600': 1600,
  '100%': 'full'
};

const MessageHtmlView = ({message}: Props) => {
  const [breakpointKey, setBreakpointKey] = useState('full');
  const innerStyle =() => ({
    width: breakpoints[breakpointKey] === 'full' ? '100%' : breakpoints[breakpointKey]
  });
  return (
    <>
      <ToggleButtonGroup
        value={breakpointKey}
        exclusive
        onChange={(event: React.MouseEvent<HTMLElement>, newBreakpoint: string | null) => setBreakpointKey(newBreakpoint)}
      >
        {Object.keys(breakpoints).map((key) => (
          <ToggleButton key={key} value={key}>{key}</ToggleButton>
        ))}
      </ToggleButtonGroup>
      <PerfectScrollbar>
        <div
          style={innerStyle()}
          dangerouslySetInnerHTML={{
            __html: message.html.replace(/src="cid:(([^"]*?))"/, (
              fullMatch,
              captured,
            ) => {
              const matchedAttachments = message.attachments.filter((attachment) => attachment.contentDisposition === 'inline' && attachment.contentId === captured);
              if (matchedAttachments.length > 0) {
                const attachment = matchedAttachments[0];
                return `src="data:${attachment.contentType};base64,${attachment.content}"`;
              }
              return `src="cid:${captured}"`;
            })
          }}
        />
      </PerfectScrollbar>
    </>
  )
};

export default MessageHtmlView;
