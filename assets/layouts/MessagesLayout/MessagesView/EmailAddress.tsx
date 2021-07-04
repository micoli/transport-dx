import {Chip} from "@material-ui/core";
import React from "react";
import {Address} from "../../../graphQL/generated/graphqlRequest";

interface Props {
  recipient: Address
}

const EmailAddress = ({
  recipient,
}: Props) => {
  return (
    <Chip
      title={recipient.address}
      label={recipient.display ? recipient.display : recipient.address}
    />
  );
}

export default EmailAddress;
