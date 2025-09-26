export const priorityToString = (priority) => {
    const priorityMap = {
        1: 'High',
        2: 'Medium',
        3: 'Low',
    };
    return priorityMap[priority] || 'N/A';
}
