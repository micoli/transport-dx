import React from 'react';
import PropTypes, { InferProps } from 'prop-types';
import { makeStyles } from '@material-ui/core/styles';
import TreeView from '@material-ui/lab/TreeView';
import TreeItem from '@material-ui/lab/TreeItem';
import Typography from '@material-ui/core/Typography';
import MailIcon from '@material-ui/icons/Mail';
import DeleteIcon from '@material-ui/icons/Delete';
import Label from '@material-ui/icons/Label';
import LabelOff from '@material-ui/icons/LabelOff';
import SupervisorAccountIcon from '@material-ui/icons/SupervisorAccount';
import ArrowDropDownIcon from '@material-ui/icons/ArrowDropDown';
import ArrowRightIcon from '@material-ui/icons/ArrowRight';

const useTreeItemStyles = makeStyles((theme) => ({
  root: {
    color: theme.palette.text.secondary,
    '&:hover > $content': {
      backgroundColor: theme.palette.action.hover,
    },
    '&:focus > $content, &$selected > $content': {
      backgroundColor: `var(--tree-view-bg-color, ${theme.palette.grey[400]})`,
      color: 'var(--tree-view-color)',
    },
    '&:focus > $content $label, &:hover > $content $label, &$selected > $content $label': {
      backgroundColor: 'transparent',
    },
  },
  content: {
    color: theme.palette.text.secondary,
    borderTopRightRadius: theme.spacing(2),
    borderBottomRightRadius: theme.spacing(2),
    paddingRight: theme.spacing(1),
    fontWeight: theme.typography.fontWeightMedium,
    '$expanded > &': {
      fontWeight: theme.typography.fontWeightRegular,
    },
  },
  group: {
    marginLeft: 0,
    '& $content': {
      paddingLeft: theme.spacing(2),
    },
  },
  expanded: {},
  selected: {},
  label: {
    fontWeight: 'inherit',
    color: 'inherit',
  },
  labelRoot: {
    display: 'flex',
    alignItems: 'center',
    padding: theme.spacing(0.5, 0),
  },
  labelIcon: {
    marginRight: theme.spacing(1),
  },
  labelText: {
    fontWeight: 'inherit',
    flexGrow: 1,
  },
}));

function StyledTreeItem({
  nodeId,
  labelText,
  labelIcon: LabelIcon,
  labelInfo,
  color,
  bgColor,
  ...other
}: InferProps<typeof StyledTreeItem.propTypes>) {
  const classes = useTreeItemStyles();

  return (
    <TreeItem
      nodeId={nodeId}
      label={(
        <div className={classes.labelRoot}>
          <LabelIcon color="inherit" className={classes.labelIcon} />
          <Typography variant="body2" className={classes.labelText}>
            {labelText}
          </Typography>
          <Typography variant="caption" color="inherit">
            {labelInfo}
          </Typography>
        </div>
      )}
      style={{
        /* @ts-ignore */
        '--tree-view-color': color,
        '--tree-view-bg-color': bgColor,
      }}
      classes={{
        root: classes.root,
        content: classes.content,
        expanded: classes.expanded,
        selected: classes.selected,
        group: classes.group,
        label: classes.label,
      }}
      {...other}
    />
  );
}

StyledTreeItem.propTypes = {
  labelIcon: PropTypes.elementType.isRequired,
  labelText: PropTypes.string.isRequired,
  bgColor: PropTypes.string,
  nodeId: PropTypes.string,
  color: PropTypes.string,
  labelInfo: PropTypes.string,
};

const useStyles = makeStyles({
  root: {
    height: 264,
    flexGrow: 1,
    maxWidth: 400,
  },
});

export default function GmailTreeView({groups, className, onGroupSelected}: InferProps<typeof GmailTreeView.propTypes>) {
  const classes = useStyles();
  const allGroupNodeId = '____all_groups____';
  const noGroupNodeId = '____no_group____';

  return (
    <TreeView
      className={`${classes.root} ${className}`}
      defaultExpanded={['Groups']}
      defaultCollapseIcon={<ArrowDropDownIcon />}
      defaultExpandIcon={<ArrowRightIcon />}
      defaultEndIcon={<div style={{ width: 24 }} />}
      multiSelect={false}
      onNodeSelect={(event, nodeId) => {
        if(nodeId === allGroupNodeId){
          onGroupSelected(null);
          return
        }
        if(nodeId === noGroupNodeId){
          onGroupSelected('');
          return
        }
        onGroupSelected(nodeId);
      }}
    >
      <StyledTreeItem
        nodeId={allGroupNodeId}
        labelText="All Mail"
        labelIcon={MailIcon}
      />
      <StyledTreeItem
        nodeId={noGroupNodeId}
        labelText="Without group"
        labelIcon={LabelOff}
      />
      {/* @ts-ignore */}
      <StyledTreeItem
        nodeId="Groups"
        labelText="Groups"
        labelIcon={Label}
      >
        {groups.map((group) => group.name && (
          <StyledTreeItem
            key={group.name}
            nodeId={`${group.name}`}
            labelText={group.name}
            labelIcon={SupervisorAccountIcon}
            labelInfo={`${group.numberOfMessage}`}
            color="#1a73e8"
            bgColor="#e8f0fe"
          />
        ))}
      </StyledTreeItem>
      <StyledTreeItem
        nodeId="Trash"
        labelText="Trash"
        labelIcon={DeleteIcon}
      />
    </TreeView>
  );
}

GmailTreeView.propTypes = {
  groups: PropTypes.array,
  className: PropTypes.string,
  onGroupSelected: PropTypes.func,
}
