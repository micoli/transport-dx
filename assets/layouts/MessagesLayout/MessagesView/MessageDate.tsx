import React from "react";
import moment from "moment";
import {Tooltip} from "@material-ui/core";

interface Props {
  date: moment.Moment
}

const getFormattedDate = (date: moment.Moment): string => {
  if (date.format('YYYYWW') === moment().format('YYYYWW')) {
    return date.format('dddd HH:mm:ss');
  }
  if (date.format('YYYYMM') === moment().format('YYYYMM')) {
    return date.format('dddd DD HH:mm:ss');
  }
  return date.format('DD/MM/YYYY HH:mm:ss');
}

const MessageDate = ({date}: Props) => {
  return (
    <Tooltip title={date.format('DD/MM/YYYY HH:mm:ss')}>
      <span>{getFormattedDate(date)}</span>
    </Tooltip>
  );
}

export default MessageDate;
